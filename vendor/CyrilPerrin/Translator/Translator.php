<?php

/**
 * Translation tool
 * @author Cyril Perrin
 * @license LGPL v3
 * @version 2013-09-16
 */

namespace CyrilPerrin\Translator {

    /**
     * Translator
     */
    class Translator
    {
        
        /** @var $_singleton Translation translation tool single instance  */
        private static $_singleton;
        
        /** @var $_sentencesProvider ITranslatedSentencesProvider sentences provider */
        private $_sentencesProvider;
        
        /** @var $_storedLanguageProvider IStoredLanguageProvider stored language provider */
        private $_storedLanguageProvider;
        
        /**
         * Initialize translation tool
         * @param $sentences ITranslatedSentencesProvider sentences provider
         * @param $store IStoredLanguageProvider stored language provider
         * @param $provider ILanguageProvider language provider
         * @param $language string used language
         * @return Translator
         */
        public static function init(
            ITranslatedSentencesProvider $sentencesProvider,
            IStoredLanguageProvider $storedLanguageProvider=null,
            ILanguageProvider $languageProvider=null,$language=null)
        {            
            // Create translation tool
            Translator::$_singleton = new Translator(
                $sentencesProvider, $storedLanguageProvider,
                $languageProvider, $language
            );
            
            // Return translation tool
            return Translator::$_singleton;
        }
        
        /**
         * Get translation tool single instance
         * @return Translator
         */
        public static function getInstance()
        {
            return Translator::$_singleton;
        }
        
        /**
         * Constructor
         * @param $sentencesProvider ITranslatedSentencesProvider sentences provider
         * @param $store IStoredLanguageProvider stored language provider
         * @param $languageProvider ILanguageProvider language provider
         * @param $language string used language
         */
        private function __construct(
            ITranslatedSentencesProvider $sentencesProvider,
            IStoredLanguageProvider $storedLanguageProvider=null,
            ILanguageProvider $languageProvider=null,$language=null)
        {
            // Save providers
            $this->_sentencesProvider = $sentencesProvider;
            $this->_storedLanguageProvider = $storedLanguageProvider;
            
            // Get supported languages
            $supportedLanguages = $this->_sentencesProvider->getSupportedLanguages();
            
            // If language is not set
            if ($language == null || !in_array($language, $supportedLanguages)) {
                // Get language from store
                if ($this->_storedLanguageProvider != null) {
                    $language = $this->_storedLanguageProvider->getStoredLanguage();
                }
                
                // If there is no store or if store is empty
                if ($language == null || !in_array($language, $supportedLanguages)) {
                    // Get language from language provider
                    $language = $languageProvider->getLanguage($supportedLanguages);
                
                    // Store language
                    if ($this->_storedLanguageProvider != null) {
                        $this->_storedLanguageProvider->store($language);
                    }
                }
            } else {
                // Store language
                if ($this->_storedLanguageProvider != null) {
                    $this->_storedLanguageProvider->store($language);
                }
            }
            
            // Set used language
            $this->setLanguage($language);
        }
        
        /**
         * Get used language
         * @return string used language
         */
        public function getLanguage()
        {
            return $this->_sentencesProvider->getLanguage();
        }
        
        /**
         * Set used language
         * @param $language string used language
         * @param $store boolean store language ?
         */
        public function setLanguage($language,$store=true)
        {
            // Set used language
            $this->_sentencesProvider->setLanguage($language);
            
            // Store language
            if ($store && $this->_storedLanguageProvider != null) {
                $this->_storedLanguageProvider->store($language);
            }
        }
        
        /**
         * Get supported languages
         * @return array supported languages
         */
        public function getSupportedLanguages()
        {
            return $this->_sentencesProvider->getSupportedLanguages();
        }
        
        /**
         * Translate a sentence
         * @param $parameters ? parameters to send to translated sentences provider
         * @return string translated sentence
         */
        public function getTranslatedSentence($parameters) 
        {
            return $this->_sentencesProvider->getTranslatedSentence($parameters);
        }
    }

}

namespace
{
    // Used namespaces
    use CyrilPerrin\Translator\Translator;
    
    /**
     * Translate a sentance
     * @return string translated sentance
     */
    function tr()
    {
        return Translator::getInstance()->getTranslatedSentence(func_get_args());
    }
}
