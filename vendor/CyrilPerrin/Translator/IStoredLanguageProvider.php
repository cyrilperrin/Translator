<?php

namespace CyrilPerrin\Translator;

/**
 * Interface to implement to be considered as a stored language provider
 */
interface IStoredLanguageProvider
{

    /**
     * Get stored language
     * @return string stored language
     */
    public function getStoredLanguage();

    /**
     * Store a language
     * @param $language string language to store
    */
    public function store($language);

}