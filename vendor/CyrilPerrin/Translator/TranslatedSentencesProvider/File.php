<?php

namespace CyrilPerrin\Translator;

/**
 * Translated sentences from file
 */
class TranslatedSentencesProvider_File implements ITranslatedSentencesProvider
{

    /** @var $_reference int reference language index */
    private $_reference;

    /** @var $_language int language index */
    private $_language;

    /** @var $_supportedLanguages array supported languages */
    private $_supportedLanguages;

    /** @var $_sentences array sentences */
    private $_sentences;

    /**
     * Constructor
     * @param $filename string translations file path
     * @param $reference int reference language index
     */
    public function __construct($filename, $reference=0)
    {
        // Save reference langauge index
        $this->_reference = $reference;

        // Get sentences
        $this->_sentences = file($filename);
        foreach ($this->_sentences as $key => $sentences) {
            $this->_sentences[$key] = explode("\t", trim($sentences));
        }

        // Get supported languages
        $this->_supportedLanguages = array_map(
            'strtolower',
            array_shift($this->_sentences)
        );
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
        // Search language index in languages list
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
        if ($this->_language == $this->_reference) {
            return str_replace('\\', '', $sentence);
        }

        // Search a correspondance in sentences
        foreach ($this->_sentences as $translations) {
            // Match ?
            $pattern = '/^'.$translations[$this->_reference].'$/i';
            if (preg_match($pattern, $sentence)) {
                // Check if a translation exists
                if (!empty($translations[$this->_language])) {
                    return preg_replace(
                        $pattern, $translations[$this->_language], $sentence
                    );
                }

                // Return initial sentence if no translation exists
                return str_replace('\\', '', $sentence);
            }
        }

        // Return initial sentence if no correspondance found
        return str_replace('\\', '', $sentence);
    }

}