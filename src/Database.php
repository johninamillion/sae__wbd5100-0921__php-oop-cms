<?php

namespace CMS;

use PDO;

final class Database extends PDO {

    /**
     * @access  public
     * @constructor
     */
    public function __construct() {
        /** @var string $dsn */
        $dsn = sprintf(
            'mysql:dbname=%1$s;host=%2$s;port=%3$s',
            DB_NAME,
            DB_HOST,
            DB_PORT
        );

        parent::__construct( $dsn, DB_USER, DB_PASS );
    }

}
