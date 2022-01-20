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
     * Check if an value exists for a key in session storage
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
