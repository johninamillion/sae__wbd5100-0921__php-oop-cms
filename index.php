<?php

namespace CMS;

// Error Reporting anschalten, für eine Ausgabe von Fehlermeldungen im Browser
error_reporting( E_ALL );
ini_set( 'display_errors', '1' );

// Composer Autoloader einbinden
/** @var string $autoload_file */
$autoload_file = __DIR__ . '/vendor/autoload.php';

// Überprüfen ob die autoload.php existiert und eine Fehlermeldung zur Behebung ausgeben, sollte dies nicht der Fall sein
if ( file_exists( $autoload_file ) === FALSE ) {
    trigger_error(
        sprintf(
            _( 'No autoload.php found (%1$s). Please run \'composer update\' in the project root directory.' ),
            $autoload_file
        ),
        E_USER_ERROR
    );
}

require_once $autoload_file;
