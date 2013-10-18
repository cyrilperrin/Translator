<?php

namespace CyrilPerrin\Translator;

/**
 * Interface to implement to be considered as a language provider
 */
interface ILanguageProvider
{

    /**
     * Get language
     * @param $supportedLanguages array supported languages
     * @return string language
     */
    public function getLanguage($supportedLanguages);

}