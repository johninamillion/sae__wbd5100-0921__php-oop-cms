<?php

namespace CMS\Model;

use CMS\Controller\Error;
use CMS\Errors;
use CMS\Model;
use CMS\Session;

final class User extends Model {

    /**
     * Compare password from user input with hashed password from users table
     * @access  private
     * @param   string|NULL $password
     * @param   array       $credentials
     * @return  bool
     */
    private function comparePasswords( ?string $password, array $credentials ) : bool {
        /** @var string $hashed_password */
        $hashed_password = $this->createHashedPassword( $password, $credentials[ 'salt' ] );

        // Überprüfen ob die Passwörter übereinstimmen, und eine Fehlermeldung speichern wenn nicht
        if ( $hashed_password !== $credentials[ 'password' ] ) {
            Errors::addError( 'login', _( 'Wrong password' ) );
        }

        return Errors::hasErrors( 'login' ) === FALSE;
    }

    /**
     * Hash user password with salt
     * @access  private
     * @param   string      $password
     * @param   string|NULL $salt
     * @return  string
     */
    private function createHashedPassword( string $password, ?string $salt ) : string {
        /** @var string $data */
        $data = "{$password}{$salt}";

        return $this->hash( $data );
    }

    /**
     * Create salt
     * @access  private
     * @return  string
     */
    private function createSalt() : string {
        /** @var int $time */
        $time = time();
        /** @var int $rand */
        $rand = rand( 1234, 9876 );
        /** @var string $data */
        $data = "{$time}{$rand}";

        return $this->hash( $data );
    }

    /**
     * Check if a email already exists in the users table
     * @access  private
     * @param   string|NULL $email
     * @return  string
     */
    private function emailExists( ?string $email ) : bool {
        /** @var string $query */
        $query = 'SELECT email FROM users WHERE email = :email';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindParam( ':email', $email );
        $Statement->execute();

        return $Statement->rowCount() > 0;
    }

    /**
     * Get password and salt from users table by username
     * @access  private
     * @param   string|NULL $username
     * @return  array
     */
    private function getCredentials( ?string $username ) : array {
        /** @var string $query */
        $query = 'SELECT id, password, salt FROM users WHERE username = :username';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindParam( ':username', $username );
        $Statement->execute();

        return $Statement->rowCount() > 0 ? $Statement->fetch() : [ 'id' => NULL, 'password' => NULL, 'salt' => NULL ];
    }

    /**
     * Hash data
     * @access  private
     * @param   string  $data
     * @return  string
     */
    private function hash( string $data ) : string {

        return hash( 'sha512', $data );
    }

    /**
     * Check if a username already exists in the users table
     * @access  private
     * @param   string|NULL $username
     * @return  bool
     */
    private function usernameExists( ?string $username ) : bool {
        /** @var string $query */
        $query = 'SELECT username FROM users WHERE username = :username';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindParam( ':username', $username );
        $Statement->execute();

        return $Statement->rowCount() > 0;
    }

    /**
     * Validate email user input
     * @access  private
     * @param   string|NULL $email
     * @return  bool
     */
    private function validateEmail( ?string $email ) : bool {
        // Überprüfen ob eine E-Mail Adresse eingegeben wurde
        if ( is_null( $email ) === TRUE ) {
            Errors::addError( 'email', _( 'Please type in a valid E-Mail address' ) );
        }
        // Überprüfen ob die E-Mail Adresse bereits für einen Nutzer existiert
        if ( $this->emailExists( $email ) === TRUE ) {
            Errors::addError( 'email', _( 'E-Mail address already exists' ) );
        }
        // Überprüfen ob die E-Mail Adresse valide ist
        if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) === FALSE ) {
            Errors::addError( 'email', _( 'E-Mail address should be valid' ) );
        }

        return Errors::hasErrors( 'email' ) === FALSE;
    }

    /**
     * Validate password user input
     * @access  private
     * @param   string|NULL $password
     * @param   string|NULL $password_repeat
     * @return  bool
     */
    private function validatePassword( ?string $password, ?string $password_repeat ) : bool {
        // Überprüfen ob ein Passwort eingegeben wurde
        if ( is_null( $password ) === TRUE ) {
            Errors::addError( 'password', _( 'Please type in a valid password' ) );
        }
        // Überprüfen ob ein Passwort wiederholteingegeben wurde
        if ( is_null( $password_repeat ) === TRUE ) {
            Errors::addError( 'password_repeat', _( 'Please repeat a valid password' ) );
        }
        // Überprüfen ob das Passwort mindestens 8 Zeichen lang ist
        if ( strlen( $password ) < 8 ) {
            Errors::addError( 'password', _( 'Password should be minimum 8 characters long' ) );
        }
        // Überprüfen ob die Eingabe der Wiederholung dem eingegebenen Passwort entspricht
        if ( $password !== $password_repeat ) {
            Errors::addError( 'password_repeat', _( 'Please repeat a the password' ) );
        }
        // Überprüfen ob das Passwort leerzeichen enthält
        if ( preg_match( '/\s/', $password ) == TRUE ) {
            Errors::addError( 'password', _( 'Password should not contain any whitespace' ) );
        }
        // Überprüfen ob das Passwort Kleinbuchstaben enthält
        if ( preg_match( '/[a-z]/', $password ) == FALSE ) {
            Errors::addError( 'password', _( 'Password should contain minimum one small letter' ) );
        }
        // Überprüfen ob das Passwort Großbuchstaben enthält
        if ( preg_match( '/[A-Z]/', $password ) == FALSE ) {
            Errors::addError( 'password', _( 'Password should contain minimum one capital letter' ) );
        }
        // Überprüfen ob das Passwort Zahlen enthält
        if ( preg_match( '/\d/', $password ) == FALSE ) {
            Errors::addError( 'password', _( 'Password should contain minimum one digit' ) );
        }
        // Überprüfen ob das Passwort Sonderzeichen enthält
        if ( preg_match( '/\W/', $password ) == FALSE ) {
            Errors::addError( 'password', _( 'Password should contain minimum one special character' ) );
        }

        return Errors::hasErrors( 'password' ) === FALSE && Errors::hasErrors( 'password_repeat' ) === FALSE;
    }

    /**
     * Validate username user input
     * @access  private
     * @param   string|NULL $username
     * @return  bool
     */
    private function validateUsername( ?string $username ) : bool {
        // Überprüfen ob ein Nutzername eingegeben wurde
        if ( is_null( $username ) === TRUE ) {
            Errors::addError( 'username', _( 'Please type in a valid username' ) );
        }
        // Überprüfen ob der Nutzername bereits für einen Nutzer existiert
        if ( $this->usernameExists( $username ) === TRUE ) {
            Errors::addError( 'username', _( 'Username already exists' ) );
        }
        // Überprüfen ob der Nutzername mindestens 4 Zeichen enthält
        if ( strlen( $username ) < 4 ) {
            Errors::addError( 'username', _( 'Username should be minimum 4 characters long' ) );
        }
        // Überprüfen ob der Nutzername maximal 16 Zeichen enthält
        if ( strlen( $username ) > 16 ) {
            Errors::addError( 'username', _( 'Username should be maxmimum 16 characters long' ) );
        }

        return Errors::hasErrors( 'username' ) === FALSE;
    }

    /**
     * Delete user account
     * @access  public
     * @return  bool
     */
    public function delete() : bool {
        /** @var int $user_id */
        $user_id = $_SESSION[ 'login' ][ 'id' ];
        /** @var string $username */
        $username = $_SESSION[ 'login' ][ 'username' ];
        /** @var ?string $password */
        $password = filter_input( INPUT_POST , 'password' );

        /** @var array $credentials */
        $credentials = $this->getCredentials( $username );
        /** @var bool $comparison */
        $comparison = $this->comparePasswords( $password, $credentials );

        // Überprüfen ob das vom Nutzereingegebene Passwort übereinstimmt und Nutzer löschen wenn ja
        if ( $comparison === TRUE ) {
            /** @var string $query */
            $query = 'DELETE FROM users WHERE id = :id';

            /** @var \PDOStatement $Statement */
            $Statement = $this->Database->prepare( $query );
            $Statement->bindParam( ':id', $user_id );
            $Statement->execute();

            return $Statement->rowCount() > 0;
        }

        return FALSE;
    }

    /**
     * @access  public
     * @return  bool
     */
    public function logout() : bool {
        Session::logout();

        return TRUE;
    }

    /**
     * @access  public
     * @return  bool
     */
    public function login() : bool {
        /** @var ?string $username */
        $username = filter_input( INPUT_POST, 'username' );
        /** @var ?string $password */
        $password = filter_input( INPUT_POST, 'password' );

        /** @var array $credentials */
        $credentials = $this->getCredentials( $username );
        /** @var bool $comparison */
        $comparison = $this->comparePasswords( $password, $credentials );

        // Überprüfen ob das vom Nutzer eingegebene Passwort mit dem in der users Tabelle gespeicherten übereinstimmt
        if ( $comparison === TRUE ) {
            Session::login( $credentials[ 'id'], $username );

            return TRUE;
        }

        return FALSE;
    }

    /**
     * @access  public
     * @return  bool
     */
    public function register() : bool {
        /** @var ?string $username */
        $username = filter_input( INPUT_POST, 'username' );
        /** @var ?string $email */
        $email = filter_input( INPUT_POST, 'email' );
        /** @var ?string $password */
        $password = filter_input( INPUT_POST, 'password' );
        /** @var ?string $password_repeat */
        $password_repeat = filter_input( INPUT_POST, 'password_repeat' );

        /** @var bool $validate_username */
        $validate_username = $this->validateUsername( $username );
        /** @var bool $validate_email */
        $validate_email = $this->validateEmail( $email );
        /** @var bool $validate_password */
        $validate_password = $this->validatePassword( $password, $password_repeat );

        // Überprüfen ob die Nutzereingaben valide sind
        if ( $validate_username && $validate_email && $validate_password ) {
            /** @var string $salt */
            $salt = $this->createSalt();
            /** @var string $hashed_password */
            $hashed_password = $this->createHashedPassword( $password, $salt );
            /** @var int $registered */
            $registered = $_SERVER[ 'REQUEST_TIME' ];

            /** @var string $query */
            $query = 'INSERT INTO users ( username, email, password, salt, registered ) VALUES ( :username, :email, :password, :salt, :registered )';

            /** @var \PDOStatement $Statement */
            $Statement = $this->Database->prepare( $query );
            $Statement->bindValue( ':username', $username );
            $Statement->bindValue( ':email', $email );
            $Statement->bindValue( ':password', $hashed_password );
            $Statement->bindValue( ':salt', $salt );
            $Statement->bindValue( ':registered', $registered );
            $Statement->execute();

            return $Statement->rowCount() > 0;
        }

        return FALSE;
    }

    /**
     * Update user password
     * @access  public
     * @return  bool
     */
    public function updatePassword() : bool {
        /** @var string $user_id */
        $user_id = $_SESSION[ 'login' ][ 'id' ];
        /** @var string $username */
        $username = $_SESSION[ 'login' ][ 'username' ];
        /** @var ?string $password */
        $password = filter_input( INPUT_POST, 'password' );
        /** @var ?string $new_password */
        $new_password = filter_input( INPUT_POST, 'new_password' );
        /** @var ?string $new_password_repeat */
        $new_password_repeat = filter_input( INPUT_POST, 'new_password_repeat' );

        /** @var array $credentials */
        $credentials = $this->getCredentials( $username );
        /** @var bool $comparison */
        $comparison = $this->comparePasswords( $password, $credentials );
        /** @var bool $validate_new_password */
        $validate_new_password = $this->validatePassword( $new_password, $new_password_repeat );

        // Überprüfen ob das vom Nutzer eingegebene Passwort stimmt und die neuen Passwörter valide sind
        if ( $comparison && $validate_new_password ) {
            /** @var string $new_salt */
            $new_salt = $this->createSalt();
            /** @var string $new_hashed_password */
            $new_hashed_password = $this->createHashedPassword( $new_password, $new_salt );

            /** @var string $query */
            $query = 'UPDATE users SET password = :password, salt = :salt WHERE id = :id';
            /** @var \PDOStatement $Statement */
            $Statement = $this->Database->prepare( $query );
            $Statement->bindValue( ':password', $new_hashed_password );
            $Statement->bindValue( ':salt', $new_salt );
            $Statement->bindParam( ':id', $user_id );
            $Statement->execute();

            return $Statement->rowCount() > 0;
        }

        return FALSE;
    }

}
