<?php

namespace CyrilPerrin\Translator;

/**
 * Translated sentences from file accessible by regular expressions
 */
class TranslatedSentencesProvider_File_Regex implements ITranslatedSentencesProvider
{

    /** @var $_language int language index */
    private $_language;

    /** @var $_supportedLanguages array supported languages */
    private $_supportedLanguages;

    /** @var $_sentences array sentences */
    private $_sentences;

    /**
     * Constructor
     * @param $filename string translations file path
     */
    public function __construct($filename)
    {
        // Get file content
        $lines = file($filename);

        // Get supported languages
        $this->_supportedLanguages = array_map(
            'strtolower', explode("\t", trim(array_shift($lines)))
        );
        
        // Get sentences
        $this->_sentences = array();
        foreach ($lines as $key => $translations) {
            $this->_sentences[$key] = explode("\t", trim($translations));
        }
    }
    
    /**
     * @see ITranslatedSentencesProvider::getLanguage()
     */
    public function getLanguage()
    {
        return $this->_supportedLanguages[$this->_language];
    }

    /**
     * @see ITranslatedSentencesProvider::setLanguage()
     */
    public function setLanguage($language)
    {
        $this->_language = array_search(
            strtolower($language),
            $this->_supportedLanguages
        );
    }
    
    /**
     * @see ITranslatedSentencesProvider::getSupportedLanguages()
     */
    public function getSupportedLanguages()
    {
        return $this->_supportedLanguages;
    }

    /**
     * @see ITranslatedSentencesProvider::getTranslatedSentence()
     */
    public function getTranslatedSentence($parameters)
    {
        // Get initial sentence
        $sentence = array_shift($parameters);
        
        // Return initial sentence if language is reference
        if ($this->_language == 0) {
            return str_replace('\\', '', $sentence);
        }

        // Search a correspondance in sentences
        foreach ($this->_sentences as $translations) {
            // Build pattern
            $pattern = '/^'.$translations[0].'$/i';
            
            // Match to pattern ?
            if (preg_match($pattern, $sentence)) {
                // Check if a translation exists
                if (!empty($translations[$this->_language])) {
                    return preg_replace($pattern, $translations[$this->_language], $sentence);
                }

                // Return initial sentence if no translation exists
                return str_replace('\\', '', $sentence);
            }
        }

        // Return initial sentence if no correspondance found
        return str_replace('\\', '', $sentence);
    }

}