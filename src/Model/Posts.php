<?php

namespace CMS\Model;

use CMS\Messages;
use CMS\Model;
use CMS\Session;

final class Posts extends Model {

    /**
     * Validate post message
     * @access  private
     * @param   string|NULL $message
     * @param   string|NULL $error_key
     * @return  bool
     */
    private function validateMessage( ?string $message, ?string $error_key = 'message' ) : bool {
        // Überprüfen ob eine Nachricht eingegeben wurde
        if ( is_null( $message ) === TRUE ) {
            Messages::addError( $error_key, _( 'Please type in a valid message' ) );
        }
        // Überprüfen ob die Nachricht mindestens 8 Zeichen enthält
        if ( strlen( $message ) < 8 ) {
            Messages::addError( $error_key, _( 'The message should be minimum 8 characters long' ) );
        }
        // Überprüfen ob die Nachricht maximal 480 Zeichen enthält
        if ( strlen( $message ) > 480 ) {
            Messages::addError( $error_key, _( 'The message should be maximum 480 characters long' ) );
        }

        return Messages::hasErrors( $error_key ) === FALSE;
    }

    /**
     * Validate post title
     * @access  private
     * @param   string|NULL $title
     * @param   string|NULL $error_key
     * @return  bool
     */
    private function validateTitle( ?string $title, ?string $error_key = 'title' ) : bool {
        // Überprüfen ob ein Titel eingegeben wurde
        if ( is_null( $title ) === TRUE ) {
            Messages::addError( $error_key, _( 'Please type in a valid title' ) );
        }
        // Überprüfen ob der Titel mindestens 8 Zeichen enthält
        if ( strlen( $title ) < 8 ) {
            Messages::addError( $error_key, _( 'The title should be minimum 8 characters long' ) );
        }
        // Überprüfen ob der Titel maximal 40 Zeichen enthält
        if ( strlen( $title ) > 40 ) {
            Messages::addError( $error_key, _( 'The title should be maximum 40 characters long' ) );
        }

        return Messages::hasErrors( $error_key ) === FALSE;
    }

    /**
     * Create a post
     * @access  public
     * @return  bool
     */
    public function createPost() : bool {
        /** @var array|NULL $login */
        $login = Session::getValue( 'login' );
        /** @var ?string $title */
        $title = filter_input( INPUT_POST, 'title' );
        /** @var ?string $message */
        $message = filter_input( INPUT_POST, 'message' );
        /** @var int $created */
        $created = $_SERVER[ 'REQUEST_TIME' ] ?? time();

        /** @var bool $validate_title */
        $validate_title = $this->validateTitle( $title );
        /** @var bool $validate_message */
        $validate_message = $this->validateMessage( $message );

        if ( $validate_title && $validate_message ) {
            /** @var string $query */
            $query = 'INSERT INTO posts ( user_id, title, message, created ) VALUES ( :user_id, :title, :message, :created )';

            /** @var \PDOStatement $Statement */
            $Statement = $this->Database->prepare( $query );
            $Statement->bindValue( ':user_id', $login[ 'id' ] );
            $Statement->bindValue( ':title', $title );
            $Statement->bindValue( ':message', $message );
            $Statement->bindValue( ':create', $created );
            $Statement->execute();

            /** @var bool $success */
            $success = $Statement->rowCount() > 0;

            if ( $success ) {
                Messages::addSuccess( 'create_post', _( 'Your post is published!' ) );
            }

            return $success;
        }

        return FALSE;
    }

}
