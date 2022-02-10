<?php

namespace CMS\Model;

use Cassandra\Varint;
use CMS\Messages;
use CMS\Model;
use CMS\Session;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

final class User extends Model {

    /**
     * @access  private
     * @param   string  $email
     * @param   int     $user_id
     * @return  bool
     */
    private function addUserVerification( string $email, int $user_id ) : bool {
        /** @var string $verifiation_id */
        $verifiation_id = $this->createVerificationId( $user_id );

        /** @var string $query */
        $query = 'INSERT INTO user_verification ( user_id, verification_id ) VALUES ( :user_id, :verification_id )';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindValue( ':user_id', $user_id );
        $Statement->bindValue( ':verification_id', $verifiation_id );
        $Statement->execute();

        /** @var bool $success */
        $success = $Statement->rowCount() > 0 && $this->sendUserVerificationMail( $email, $verifiation_id );

        if ( $success ) {

        }

        return $success;
    }

    /**
     * Compare password from user input with hashed password from users table
     * @access  private
     * @param   string|NULL $password
     * @param   array       $credentials
     * @param   string      $error_key
     * @return  bool
     */
    private function comparePasswords( ?string $password, array $credentials, string $error_key = 'login' ) : bool {
        /** @var string $hashed_password */
        $hashed_password = $this->createHashedPassword( $password, $credentials[ 'salt' ] );

        // Überprüfen ob die Passwörter übereinstimmen, und eine Fehlermeldung speichern wenn nicht
        if ( $hashed_password !== $credentials[ 'password' ] ) {
            Messages::addError( $error_key, _( 'Wrong password' ) );
        }

        return Messages::hasErrors( $error_key ) === FALSE;
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
     * @access  private
     * @param   int     $user_id
     * @return  string
     */
    private function createVerificationId( int $user_id ) : string {
        /** @var int $time */
        $time = time();
        /** @var int $rand */
        $rand = rand( 1234, 9876 );

        return hash( 'sha512', "{$user_id}-{$time}-{$rand}" );
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
        $query = 'SELECT id, password, salt FROM users WHERE username = :username AND verified = 1';

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
     * @access  private
     * @param   string  $email
     * @param   string  $verification_id
     * @return  bool
     */
    private function sendUserVerificationMail( string $email, string $verification_id ) : bool {
        /** @var string $message */
        $message = sprintf(
            '<a href="%1$s">%2$s</a>',
            APPLICATION_URL . "/verification?id={$verification_id}",
            _( 'Confirm Registration' )
        );

        /** @var PHPMailer $Mail */
        $Mail = new PHPMailer( TRUE );

        try {
            $Mail->setFrom( 'noreply@millionvisions.de' );
            $Mail->addReplyTo( 'noreply@millionvisions.de' );
            $Mail->addAddress( $email );

            $Mail->isHTML( true );
            $Mail->Subject = _( 'User Verification' );
            $Mail->Body = $message;

            return $Mail->send();
        }
        catch ( Exception $exception ) {
            var_dump( $exception );
        }
    }

    /**
     * Validate email user input
     * @access  private
     * @param   string|NULL $email
     * @param   string      $error_key
     * @return  bool
     */
    private function validateEmail( ?string $email, string $error_key = 'email' ) : bool {
        // Überprüfen ob eine E-Mail Adresse eingegeben wurde
        if ( is_null( $email ) === TRUE ) {
            Messages::addError( $error_key, _( 'Please type in a valid E-Mail address' ) );
        }
        // Überprüfen ob die E-Mail Adresse bereits für einen Nutzer existiert
        if ( $this->emailExists( $email ) === TRUE ) {
            Messages::addError( $error_key, _( 'E-Mail address already exists' ) );
        }
        // Überprüfen ob die E-Mail Adresse valide ist
        if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) === FALSE ) {
            Messages::addError( $error_key, _( 'E-Mail address should be valid' ) );
        }

        return Messages::hasErrors( $error_key ) === FALSE;
    }

    /**
     * Validate password user input
     * @access  private
     * @param   string|NULL $password
     * @param   string|NULL $password_repeat
     * @param   string      $error_key
     * @param   string      $error_key_repeat
     * @return  bool
     */
    private function validatePassword( ?string $password, ?string $password_repeat, string $error_key = 'password', string $error_key_repeat = 'password_repeat' ) : bool {
        // Überprüfen ob ein Passwort eingegeben wurde
        if ( is_null( $password ) === TRUE ) {
            Messages::addError( $error_key, _( 'Please type in a valid password' ) );
        }
        // Überprüfen ob ein Passwort wiederholteingegeben wurde
        if ( is_null( $password_repeat ) === TRUE ) {
            Messages::addError( $error_key_repeat, _( 'Please repeat a valid password' ) );
        }
        // Überprüfen ob das Passwort mindestens 8 Zeichen lang ist
        if ( strlen( $password ) < 8 ) {
            Messages::addError( $error_key, _( 'Password should be minimum 8 characters long' ) );
        }
        // Überprüfen ob die Eingabe der Wiederholung dem eingegebenen Passwort entspricht
        if ( $password !== $password_repeat ) {
            Messages::addError( $error_key_repeat, _( 'Please repeat a the password' ) );
        }
        // Überprüfen ob das Passwort leerzeichen enthält
        if ( preg_match( '/\s/', $password ) == TRUE ) {
            Messages::addError( $error_key, _( 'Password should not contain any whitespace' ) );
        }
        // Überprüfen ob das Passwort Kleinbuchstaben enthält
        if ( preg_match( '/[a-z]/', $password ) == FALSE ) {
            Messages::addError( $error_key, _( 'Password should contain minimum one small letter' ) );
        }
        // Überprüfen ob das Passwort Großbuchstaben enthält
        if ( preg_match( '/[A-Z]/', $password ) == FALSE ) {
            Messages::addError( $error_key, _( 'Password should contain minimum one capital letter' ) );
        }
        // Überprüfen ob das Passwort Zahlen enthält
        if ( preg_match( '/\d/', $password ) == FALSE ) {
            Messages::addError( $error_key, _( 'Password should contain minimum one digit' ) );
        }
        // Überprüfen ob das Passwort Sonderzeichen enthält
        if ( preg_match( '/\W/', $password ) == FALSE ) {
            Messages::addError( $error_key, _( 'Password should contain minimum one special character' ) );
        }

        return Messages::hasErrors( $error_key ) === FALSE && Messages::hasErrors( $error_key_repeat ) === FALSE;
    }

    /**
     * Validate username user input
     * @access  private
     * @param   string|NULL $username
     * @param   string      $error_key
     * @return  bool
     */
    private function validateUsername( ?string $username, string $error_key = 'username' ) : bool {
        // Überprüfen ob ein Nutzername eingegeben wurde
        if ( is_null( $username ) === TRUE ) {
            Messages::addError( $error_key, _( 'Please type in a valid username' ) );
        }
        // Überprüfen ob der Nutzername bereits für einen Nutzer existiert
        if ( $this->usernameExists( $username ) === TRUE ) {
            Messages::addError( $error_key, _( 'Username already exists' ) );
        }
        // Überprüfen ob der Nutzername mindestens 4 Zeichen enthält
        if ( strlen( $username ) < 4 ) {
            Messages::addError( $error_key, _( 'Username should be minimum 4 characters long' ) );
        }
        // Überprüfen ob der Nutzername maximal 16 Zeichen enthält
        if ( strlen( $username ) > 16 ) {
            Messages::addError( $error_key, _( 'Username should be maxmimum 16 characters long' ) );
        }

        return Messages::hasErrors( $error_key ) === FALSE;
    }

    /**
     * Delete user account
     * @access  public
     * @param   string  $password
     * @return  bool
     */
    public function delete( string $password ) : bool {
        /** @var int $user_id */
        $user_id = $_SESSION[ 'login' ][ 'id' ];
        /** @var string $username */
        $username = $_SESSION[ 'login' ][ 'username' ];

        /** @var array $credentials */
        $credentials = $this->getCredentials( $username );
        /** @var bool $comparison */
        $comparison = $this->comparePasswords( $password, $credentials, 'delete' );

        // Überprüfen ob das vom Nutzereingegebene Passwort übereinstimmt und Nutzer löschen wenn ja
        if ( $comparison === TRUE ) {
            /** @var string $query */
            $query = 'DELETE i FROM images AS i LEFT JOIN users AS u ON i.id = u.image_id WHERE u.id = :user_id;' // Bilder von der images Tabelle löschen, in relation zum Nutzer
                   . 'DELETE i FROM images AS i LEFT JOIN posts AS p ON i.id = p.image_id WHERE p.user_id = :user_id;' // Bilder von der images Tabelle löschen, in relation zu Nutzerbeiträgen
                   . 'DELETE FROM users WHERE id = :user_id;' // Nutzer von der users Tabelle löschen
                   . 'DELETE FROM posts WHERE user_id = :user_id;' // Beiträge vom Nutzer aus der posts Tabelle löschen
                   . 'DELETE FROM likes WHERE user_id = :user_id;' // Likes vom Nutzer aus der likes Tabelle löschen
                   . 'DELETE FROM comments WHERE user_id = :user_id;' // Kommentare vom Nutzer aus der comments Tabelle löschen
                   . 'DELETE likes FROM likes LEFT JOIN posts ON likes.post_id = posts.id WHERE posts.user_id = :user_id;' // Likes von Nutzerbeiträgen aus der likes Tabelle löschen
                   . 'DELETE comments FROM comments LEFT JOIN posts ON comments.post_id = posts.id WHERE posts.user_id = :user_id;'; // Kommentare von Nutzerbeiträgen aus der likes Tabelle löschen

            /** @var \PDOStatement $Statement */
            $Statement = $this->Database->prepare( $query );
            $Statement->bindParam( ':user_id', $user_id );
            $Statement->execute();

            return $Statement->rowCount() > 0;
        }

        return FALSE;
    }

    /**
     * @access  public
     * @param string $username
     * @return array
     */
    public function getProfileByUsername( string $username ) : array {
        /** @var string $query */
        $query = 'SELECT u.id AS id, u.username AS username, u.registered AS registered,'
            . ' i.path AS avatar, i.thumbnails AS thumbnails,'
            . ' COUNT( c.user_id ) AS comments,'
            . ' COUNT( l.user_id ) AS likes,'
            . ' COUNT( p.user_id ) AS posts'
            . ' FROM users AS u'
            . ' LEFT JOIN comments AS c ON c.user_id = u.id'
            . ' LEFT JOIN likes AS l ON l.user_id = u.id'
            . ' LEFT JOIN posts AS p ON p.user_id = u.id'
            . ' LEFT JOIN images AS i ON u.image_id = i.id'
            . ' WHERE u.username = :username'
            . ' LIMIT 1';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindParam( ':username', $username );
        $Statement->execute();

        return $Statement->fetch();
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
     * @param   string  $username
     * @param   string  $password
     * @return  bool
     */
    public function login( string $username, string $password ) : bool {

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
     * @param   string  $username
     * @param   string  $email
     * @param   string  $password
     * @param   string  $password_repeat
     * @return  bool
     */
    public function register( string $username, string $email, string $password, string $password_repeat ) : bool {
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
            $query = 'INSERT INTO users ( username, email, password, salt, registered ) VALUES ( :username, :email, :password, :salt, :registered );';
            /** @var \PDOStatement $Statement */
            $Statement = $this->Database->prepare( $query );
            $Statement->bindValue( ':username', $username );
            $Statement->bindValue( ':email', $email );
            $Statement->bindValue( ':password', $hashed_password );
            $Statement->bindValue( ':salt', $salt );
            $Statement->bindValue( ':registered', $registered );
            $Statement->execute();

            /** @var int $user_id */
            $user_id =  $this->Database->lastInsertId();
            /** @var bool $success */
            $success = $Statement->rowCount() > 0 && $this->addUserVerification( $email, $user_id );

            if ( $success ) {
                Messages::addSuccess( 'register', _( 'You are successfully registered' ) );
            }

            return $success;
        }

        return FALSE;
    }

    /**
     * @access  public
     * @param   string  $keyword
     * @return  array
     */
    public function searchUser( string $keyword ) : array {
        /** @var string $query */
        $query = 'SELECT u.username AS username, u.registered AS registered,'
               . ' COUNT( c.user_id ) AS comments,'
               . ' COUNT( l.user_id ) AS likes,'
               . ' COUNT( p.user_id ) AS posts'
               . ' FROM users AS u'
               . ' LEFT JOIN comments AS c ON c.user_id = u.id'
               . ' LEFT JOIN likes AS l ON l.user_id = u.id'
               . ' LEFT JOIN posts AS p ON p.user_id = u.id'
               . ' WHERE u.username LIKE (:keyword) OR u.email LIKE (:keyword)'
               . ' GROUP BY u.id'
               . ' ORDER BY posts DESC';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindValue( ':keyword', "%{$keyword}%" );
        $Statement->execute();

        return $Statement->fetchAll();
    }

    /**
     * @access  public
     * @param   int|NULL    $image_id
     * @return  bool
     */
    public function updateAvatar( ?int $image_id ) : bool {
        /** @var int $user_id */
        $user_id = $_SESSION[ 'login' ][ 'id' ];

        /** @var bool $validate_image */
        $validate_image = TRUE; // TODO add validation for image

        if ( $validate_image ) {
            /** @var string $query */
            $query = 'UPDATE users SET image_id = :image_id WHERE id = :id';

            $Statement = $this->Database->prepare( $query );
            $Statement->bindValue( ':image_id' ,$image_id );
            $Statement->bindParam( ':id', $user_id );
            $Statement->execute();

            /** @var bool $success */
            $success = $Statement->rowCount() > 0;

            if ( $success ) {
                Messages::addSuccess( 'update_avatar', _( 'Your new image is saved!' ) );
            }

            return $success;
        }

        return FALSE;
    }

    /**
     * Update user email
     * @access  public
     * @param   string  $new_email
     * @return  bool
     */
    public function updateEmail( string $new_email ) : bool {
        /** @var int $user_id */
        $user_id = $_SESSION[ 'login' ][ 'id' ];

        /** @var bool $validate_email */
        $validate_email = $this->validateEmail( $new_email, 'new_email' );

        // Überprüfen ob die neue E-Mail Adresse valide ist und diese speichern wenn ja
        if ( $validate_email ) {
            /** @var string $query */
            $query = 'UPDATE users SET email = :email WHERE id = :id';

            /** @var \PDOStatement $Statement */
            $Statement = $this->Database->prepare( $query );
            $Statement->bindValue( ':email', $new_email );
            $Statement->bindParam( ':id', $user_id );
            $Statement->execute();

            /** @var bool $success */
            $success = $Statement->rowCount() > 0;

            if ( $success ) {
                Messages::addSuccess( 'update_email', _( 'E-Mail address saved successfully' ) );
            }

            return $success;
        }

        return FALSE;
    }

    /**
     * Update username
     * @access  public
     * @param   string  $new_username
     * @return  bool
     */
    public function updateUsername( string $new_username ) : bool {
        /** @var int $user_id */
        $user_id = $_SESSION[ 'login' ][ 'id' ];

        /** @var bool $validate_username */
        $validate_username = $this->validateUsername( $new_username, 'new_username' );

        // Überprüfen ob der neue Nutzername valide ist und diesen speichern wenn ja
        if ( $validate_username ) {
            /** @var string $query */
            $query = 'UPDATE users SET username = :username WHERE id = :id';

            /** @var \PDOStatement $Statement */
            $Statement = $this->Database->prepare( $query );
            $Statement->bindValue( ':username', $new_username );
            $Statement->bindParam( ':id', $user_id );
            $Statement->execute();

            /** @var bool $success */
            $success = $Statement->rowCount() > 0;

            if ( $success ) {
                Session::login( $user_id, $new_username );
                Messages::addSuccess( 'update_username', _( 'Username saved succesfully' ) );
            }

            return $success;
        }

        return FALSE;
    }

    /**
     * Update user password
     * @access  public
     * @param   string  $password
     * @param   string  $new_password
     * @param   string  $new_password_repeat
     * @return  bool
     */
    public function updatePassword( string $password, string $new_password, string $new_password_repeat ) : bool {
        /** @var string $user_id */
        $user_id = $_SESSION[ 'login' ][ 'id' ];
        /** @var string $username */
        $username = $_SESSION[ 'login' ][ 'username' ];

        /** @var array $credentials */
        $credentials = $this->getCredentials( $username );
        /** @var bool $comparison */
        $comparison = $this->comparePasswords( $password, $credentials, 'update_password' );
        /** @var bool $validate_new_password */
        $validate_new_password = $this->validatePassword( $new_password, $new_password_repeat, 'new_password', 'new_password_repeat' );

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

            /** @var bool $success */
            $success = $Statement->rowCount() > 0;

            if ( $success ) {
                Messages::addSuccess( 'update_password', _( 'Password saved successfully' ) );
            }

            return $success;
        }

        return FALSE;
    }

    /**
     * Check if a username already exists in the users table
     * @access  public
     * @param   string|NULL $username
     * @return  bool
     */
    public function usernameExists( ?string $username ) : bool {
        /** @var string $query */
        $query = 'SELECT username FROM users WHERE username = :username';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindParam( ':username', $username );
        $Statement->execute();

        return $Statement->rowCount() > 0;
    }

    /**
     * @access  public
     * @param   string  $verification_id
     * @return  bool
     */
    public function verifiyUser( string $verification_id ) : bool {
        /** @var string $query */
        $query = 'UPDATE users AS u '
               . ' LEFT JOIN user_verification AS uv ON uv.user_id = u.id'
               . ' SET u.verified = 1'
               . ' WHERE uv.verification_id = :verification_id;'
               . 'DELETE FROM user_verification WHERE verification_id = :verification_id';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindValue( ':verification_id', $verification_id );
        $Statement->execute();

        /** @var bool $success */
        $success = $Statement->rowCount() > 0;



        return $success;
    }

}
