<?php

namespace CyrilPerrin\Translator;

/**
 * Stored language provider from session
 */
class StoredLanguageProvider_Session implements IStoredLanguageProvider
{

    /** @var $_sessionKey string used key for session storage */
    private $_sessionKey;

    /**
     * Constructor
     * @param $sessionKey string used key for session storage
     * @param $startSession boolean start session ?
     */
    public function __construct($sessionKey='language',$startSession=true)
    {
        // Save used key for session storage
        $this->_sessionKey = $sessionKey;
        
        // Start session if necessary
        if ($startSession && !isset($_SESSION)) {
            session_start();
        }
    }

    /**
     * @see IStoredLanguageProvider::getStoredLanguage()
     */
    public function getStoredLanguage()
    {
        // Check if language is set in session
        if (isset($_SESSION[$this->_sessionKey])) {
            return $_SESSION[$this->_sessionKey];
        }

        // Return null
        return null;
    }

    /**
     * @see IStoredLanguageProvider::store()
     */
    public function store($language)
    {
        // Store language in session
        $_SESSION[$this->_sessionKey] = $language;
    }

}