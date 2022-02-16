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
        /** @var array $options */
        $options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        parent::__construct( $dsn, DB_USER, DB_PASS, $options );
    }

    /**
     * @access  public
     * @param   \PDOStatement $Statement
     * @return  int
     */
    public function absoluteRowCount( \PDOStatement $Statement ) : int {
        /** @var int $rows */
        $rows = 0;

        do {
            $rows = $rows + $Statement->rowCount();
        }
        while ( $Statement->nextRowset() );

        return $rows;
    }

}
