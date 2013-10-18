<?php

namespace CyrilPerrin\Translator;

/**
 * Interface to implement to be considered as a sentences provider
 */
interface ITranslatedSentencesProvider
{
    
    /**
     * Get used language
     * @return string used language
     */
    public function getLanguage();

    /**
     * Set used language
     * @param $language string used language
     */
    public function setLanguage($language);
    
    /**
     * Get supported languages
     * @return array supported languages
     */
    public function getSupportedLanguages();

    /**
     * Get sentence
     * @param $parameters ? parameters given by user
    */
    public function getTranslatedSentence($parameters);

}