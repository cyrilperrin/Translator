<?php

namespace CyrilPerrin\Translator;

/**
 * Language provider from browser
 */
class LanguageProvider_Browser implements ILanguageProvider
{
    
    /** @var $_default string default language */
    private $_default;
    
    
    /**
     * Constructor
     * @param $default $language default language
     */
    public function __construct($default)
    {
        // Save default language
        $this->_default = $default;
    }
    
    
    /**
     * @see ILanguageProvider::getLanguage()
     */
    public function getLanguage($supportedLanguages)
    {
        // Check if there is language in HTTP request
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            // Get accepted languages
            $acceptedLanguages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            
            // Search better accepted language matching to supported languages
            $language = null;
            $maxQ = 0;
            foreach ($acceptedLanguages as $acceptedLanguage) {
                // Parse accepted language
                $match = preg_match(
                    '/([a-z]+(-[a-z]+)?)(;q=(.+))?/', $acceptedLanguage, $matches
                );
                if ($match) {
                    // Get Q value
                    $q = isset($matches[4]) ? floatval($matches[4]) : 1;
                    
                    // Check if Q value is higher and language is supported
                    if ($q > $maxQ && in_array($matches[1], $supportedLanguages)) {
                       $maxQ = $q;
                       $language = $matches[1];
                    }  
                }
            }
            
            // Return language
            if ($language != null) {
                return $language;
            }
        }
        
        // Return default language
        return $this->_default;
    }
    
}