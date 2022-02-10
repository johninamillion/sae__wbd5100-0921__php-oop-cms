<?php

namespace CMS;

// Debugging
// Wenn DEBUG auf TRUE steht laden wir unsere Assets als nicht minifizierte Dateien (DEV)
// Wenn DEBUG auf FALSE steht laden wir unsere Assets als minifizierte Dateien (LIVE)
define( 'DEBUG',        TRUE );

// URL
define( 'APPLICATION_URL',          'http://sae.millionvisions.de' );

// Anwendungsverzeichnisse
define( 'APPLICATION_ROOT_DIR',     __DIR__ );
define( 'APPLICATION_TEMPLATE_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'templates' );
define( 'APPLICATION_UPLOAD_DIR',   __DIR__ . DIRECTORY_SEPARATOR . 'public'. DIRECTORY_SEPARATOR . 'uploads'  );

define( 'APPLICATION_UPLOAD_URI',   '/uploads' );

// Datenbankkonfiguration
define( 'DB_HOST',      'localhost' );
define( 'DB_NAME',      '' );
define( 'DB_USER',      '' );
define( 'DB_PASS',      '' );
define( 'DB_PORT',      '3306' );

// Session
define( 'LOGIN_TIMEOUT', 1800 );

// Date & Time format
define( 'DATE_FORMAT',          'd.m.Y' );
define( 'TIME_FORMAT',          'h:i' );
define( 'DATE_TIME_SEPARATOR',  ' - ' );
