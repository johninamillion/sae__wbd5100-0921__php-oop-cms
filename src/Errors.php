<?php

namespace CMS;

abstract class Errors {

    private static array $data = [];

    /**
     * Add an error message
     * @access  public
     * @static
     * @param   string  $input_name
     * @param   string  $error_message
     * @return  void
     */
    public static function addError( string $input_name, string $error_message ) : void {
        self::$data[ $input_name ][] = $error_message;
    }

    /**
     * Get all error messages for an input name or return all error messages
     * @access  public
     * @static
     * @param   string|NULL $input_name
     * @return  array
     */
    public static function getErrors( ?string $input_name = NULL ) : array {

        return $input_name !== NULL ? self::$data[ $input_name ] : self::$data;
    }

    /**
     * Check if error messages exists for an input name
     * @access  public
     * @static
     * @param   string  $input_name
     * @return  bool
     */
    public static function hasErrors( string $input_name ) : bool {

        return isset( self::$data[ $input_name ] );
    }

    /**
     * Print error messages for an input name
     * @access  public
     * @static
     * @param   string  $input_name
     * @return  void
     */
    public static function printInputErrors( string $input_name ) : void {
        // Überprüfen ob Errormeldungen vorhanden sind, wenn nicht die Funktion verlassen
        if ( self::hasErrors( $input_name ) === FALSE ) {

            return;
        }

        foreach( self::getErrors( $input_name ) as $error_message ) {
            echo "<p class=\"form__error\">{$error_message}</p>";
        }
    }

}