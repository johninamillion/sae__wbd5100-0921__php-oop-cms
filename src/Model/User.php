<?php

namespace CMS\Model;

use CMS\Model;

final class User extends Model {

    /**
     * Hash user password with salt
     * @access  private
     * @param   string  $password
     * @param   string  $salt
     * @return  string
     */
    private function createHashedPassword( string $password, string $salt ) : string {
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
    private function emailExists( ?string $email ) : string {
        /** @var string $query */
        $query = 'SELECT email FROM users WHERE email = :email';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindParam( ':email', $email );
        $Statement->execute();

        return $Statement->rowCount() > 0;
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
        // TODO: validate email

        return TRUE;
    }

    /**
     * Validate password user input
     * @access  private
     * @param   string|NULL $password
     * @param   string|NULL $password_repeat
     * @return  bool
     */
    private function validatePassword( ?string $password, ?string $password_repeat ) : bool {
        // TODO: validate password

        return TRUE;
    }

    /**
     * Validate username user input
     * @access  private
     * @param   string|NULL $username
     * @return  bool
     */
    private function validateUsername( ?string $username ) : bool {
        // TODO: validate username

        return TRUE;
    }

    /**
     * @access  public
     * @return  bool
     */
    public function logout() : bool {
        // TODO: create user logout

        return FALSE;
    }

    /**
     * @access  public
     * @return  bool
     */
    public function login() : bool {
        // TODO: create user login

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
            $Statement->bindValue( 'registered', $registered );
            $Statement->execute();

            return $Statement->rowCount() > 0;
        }

        return FALSE;
    }

}
