<?php

// Used namespaces 
use CyrilPerrin\Translator\Translator;
use CyrilPerrin\Translator\TranslatedSentencesProvider_File_Regex;
use CyrilPerrin\Translator\TranslatedSentencesProvider_File_Keys;
use CyrilPerrin\Translator\StoredLanguageProvider_Session;
use CyrilPerrin\Translator\LanguageProvider_Browser;

// Require autoload
require('autoload.php');

// Add vendor directory to include path
set_include_path(get_include_path().PATH_SEPARATOR.__DIR__.'/../vendor');

// Initialize Translator tool
$translator = Translator::init(
    new TranslatedSentencesProvider_File_Keys('translations.keys.txt'),
    //new TranslatedSentencesProvider_File_Regex('translations.regex.txt'),
    new StoredLanguageProvider_Session(),
    new LanguageProvider_Browser('en'),
    isset($_GET['language']) ? $_GET['language'] : null
);

// Get used language
$usedLanguage = $translator->getLanguage();

?>
<select onchange="window.location='?language='+this.value;">
    <?php
    // Display supported languages
    foreach ($translator->getSupportedLanguages() as $language) {
        $selected = $language == $usedLanguage ? ' selected="selected"' : '';
        echo '<option value="',$language,'"',$selected,'>',
             $language,
             '</option>';
    }
    ?>
</select>
<p>
    <?php
    echo tr('HELLO', 'Bruce');
    // echo tr('Hello Bruce !');
    ?>
</p>
<p>
    <?php
    echo tr('TEST');
    //echo tr('This is a test');
    ?>
</p>
<p>
    <?php
    echo tr('BYE');
    // echo tr('Bye');
    ?>
</p>