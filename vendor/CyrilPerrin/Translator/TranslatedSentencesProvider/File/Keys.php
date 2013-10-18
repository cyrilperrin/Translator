<?php

namespace CyrilPerrin\Translator;

/**
 * Translated sentences from file accessible by keys
 */
class TranslatedSentencesProvider_File_Keys implements ITranslatedSentencesProvider
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
            $translations = explode("\t", trim($translations));
            $key = array_shift($translations);
            $this->_sentences[$key] = $translations;
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
        // Get key
        $key = array_shift($parameters);

        // Check if there a correspondance
        if (isset($this->_sentences[$key])) {
            // Check if there is parameters
            if (count($parameters)) {
                // Get sentence
                $sentence = $this->_sentences[$key][$this->_language];

                // Insert sentence
                foreach ($parameters as $key => $value) {
                    $sentence = preg_replace(
                        '/\$'.($key+1).'(?![0-9])/',
                        $value, $sentence
                    );
                }
               
                // Return sentence
                return $sentence;
            } else {
                // Return sentence
                return $this->_sentences[$key][$this->_language];
            }
        }

        // Return key if no correspondance found
        return $key;
    }

}