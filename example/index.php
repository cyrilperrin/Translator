<?php

// Used namespaces 
use CyrilPerrin\Translator\Translator;
use CyrilPerrin\Translator\TranslatedSentencesProvider_File;
use CyrilPerrin\Translator\StoredLanguageProvider_Session;
use CyrilPerrin\Translator\LanguageProvider_Browser;

// Require autoload
require('autoload.php');

// Add vendor directory to include path
set_include_path(get_include_path().PATH_SEPARATOR.__DIR__.'/../vendor');

// Initialize Translator tool
$translator = Translator::init(
    new TranslatedSentencesProvider_File('translations.txt'),
    new StoredLanguageProvider_Session(),
    new LanguageProvider_Browser('en'),
    isset($_GET['language']) ? $_GET['language'] : null
);

// Get used language
$usedLanguage = $translator->getLanguage();

?>
<select onchange="window.location='?language='+this.value;">
    <?php
    foreach (array('en','fr') as $language) {
        $selected = $language == $usedLanguage ? ' selected="selected"' : null;
        echo '<option value="',$language,'"',$selected,'>',
             $language,
             '</option>';
    }
    ?>
</select>
<p><?php echo tr('Hello Bruce !'); ?></p>
<p><?php echo tr('This is a test'); ?></p>
<p><?php echo tr('Bye'); ?></p>