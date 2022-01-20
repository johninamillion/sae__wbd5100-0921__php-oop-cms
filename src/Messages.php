<?php

namespace CMS;

abstract class Messages {

    private static array $errorData = [];

    private static array $successData = [];

    /**
     * Add an error message
     * @access  public
     * @static
     * @param   string  $input_name
     * @param   string  $error_message
     * @return  void
     */
    public static function addError( string $input_name, string $error_message ) : void {
        self::$errorData[ $input_name ][] = $error_message;
    }

    /**
     * Add a success message
     * @access  public
     * @static
     * @param   string  $form_name
     * @param   string  $success_message
     * @return  void
     */
    public static function addSuccess( string $form_name, string $success_message ) : void {
        self::$successData[ $form_name ] = $success_message;
    }

    /**
     * Get all error messages for an input name or return all error messages
     * @access  public
     * @static
     * @param   string|NULL $input_name
     * @return  array
     */
    public static function getErrors( ?string $input_name = NULL ) : array {

        return $input_name !== NULL ? self::$errorData[ $input_name ] : self::$errorData;
    }

    /**
     * Check if error messages exists for an input name
     * @access  public
     * @static
     * @param   string  $input_name
     * @return  bool
     */
    public static function hasErrors( string $input_name ) : bool {

        return isset( self::$errorData[ $input_name ] );
    }

    /**
     * Check if a success message exists for a form name
     * @access  public
     * @static
     * @param   string  $form_name
     * @return  bool
     */
    public static function hasSuccess( string $form_name ) : bool {

        return isset( self::$successData[ $form_name ] );
    }

    /**
     * Print success message for a form name
     * @access  public
     * @static
     * @param   string  $form_name
     * @return  void
     */
    public static function printFormSuccess( string $form_name ) : void {
        // Überprüfen ob Successmeldungen vorhanden sind, wenn nicht die Funktion verlassen
        if ( self::hasSuccess( $form_name ) === FALSE ) {

            return;
        }

        /** @var string $message */
        $message = self::$successData[ $form_name ];

        echo "<p class=\"form__success-message\">{$message}</p>";
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

        echo "<ul class=\"form__errors\">";
        foreach( self::getErrors( $input_name ) as $error_message ) {
            echo "<li class=\"form__error\">{$error_message}</li>";
        }
        echo "</ul>";
    }

}
