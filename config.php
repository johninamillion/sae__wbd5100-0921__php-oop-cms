<?php

namespace CMS;

// Debugging
// Wenn DEBUG auf TRUE steht laden wir unsere Assets als nicht minifizierte Dateien (DEV)
// Wenn DEBUG auf FALSE steht laden wir unsere Assets als minifizierte Dateien (LIVE)
define( 'DEBUG',        TRUE );

// Anwendungsverzeichnisse
define( 'APPLICATION_ROOT_DIR',     __DIR__ );
define( 'APPLICATION_TEMPLATE_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'templates' );

// Datenbankkonfiguration
define( 'DB_HOST',      'localhost' );
define( 'DB_NAME',      'sae_wbd0921_5100_cms' );
define( 'DB_USER',      'root' );
define( 'DB_PASS',      'root' );
define( 'DB_PORT',      '3306' );
