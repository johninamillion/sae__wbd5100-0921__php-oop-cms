<?php

namespace CMS;

abstract class Session {

    /**
     * Add value with key to session storage
     * @access  public
     * @static
     * @param   string  $key
     * @param   mixed   $value
     * @return  void
     */
    public static function addValue( string $key, $value ) : void {
        $_SESSION[ $key ] = $value;
    }

    /**
     * Get a value if exists or return NULL;
     * @access  public
     * @static
     * @param   string  $key
     * @return  mixed
     */
    public static function getValue( string $key ) {

        return $_SESSION[ $key ] ?? NULL;
    }

    /**
     * Check if a value exists for a key in session storage
     * @access  public
     * @static
     * @param   string  $key
     * @return  bool
     */
    public static function hasValue( string $key ) : bool {

        return isset( $_SESSION[ $key ] );
    }

    /**
     * Add login information to session storage
     * @access  public
     * @static
     * @param   int     $id
     * @param   string  $username
     * @return  void
     */
    public static function login( int $id, string $username ) : void {
        self::addValue( 'login', [
            'id'        => $id,
            'timestamp' => time(),
            'username'  => $username
        ] );
    }

    /**
     * Check if user is logged in and logout user if the user was inactive
     * @access  public
     * @static
     * @return  void
     */
    public static function loginTimeout() : void {
        // Funktion verlasse, sollte der Nutzer nicht eingeloggt sein
        if ( isset( $_SESSION[ 'login' ] ) === FALSE ) {

            return;
        }

        /** @var int $now */
        $now = time();
        /** @var int $last_active */
        $last_active = $_SESSION[ 'login' ][ 'timestamp' ];
        /** @var string $timeout */
        $timeout = LOGIN_TIMEOUT; // LOGIN_TIMEOUT wird in der config.php eingestellt

        // Wir überprüfen ob das Timeout überschritten wurde und loggen den Nutzer gegebenenfalls aus
        if ( $now - $last_active > $timeout ) {
            self::logout();
        }
        // Wir speichern uns den Zeitpunkt an dem der Nutzer zum letzten mal aktiv war
        else {
            $_SESSION[ 'login' ][ 'timestamp' ] = $now;
        }
    }

    /**
     * Unset login information from session storage
     * @access  public
     * @static
     * @return  void
     */
    public static function logout() : void {
        self::unsetValue( 'login' );
    }

    /**
     * Start session
     * @access  public
     * @static
     * @return  void
     */
    public static function start() : void {
        session_start();
    }

    /**
     * Remove value by key from session storage
     * @access  public
     * @static
     * @param   string  $key
     * @return  void
     */
    public static function unsetValue( string $key ) : void {
        unset( $_SESSION[ $key ] );
    }

}
