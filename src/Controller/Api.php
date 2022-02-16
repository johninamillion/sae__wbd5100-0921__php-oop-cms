<?php

namespace CMS\Controller;

use CMS\Controller;

use CMS\Messages;
use CMS\Model\User as UserModel;

final class Api extends Controller {

    private ?UserModel $User = NULL;

    /**
     * @access  public
     * @constructor
     */
    public function __construct() {
        $this->User = new UserModel();

        parent::__construct( FALSE );
    }

    /**
     * @access  public
     * @return  void
     */
    public function index(): void {
        $this->httpResponseCode( 404 );
        exit();
    }

    /**
     * @access  public
     * @return  void
     */
    public function register() : void {
        if ( $this->isMethod( self::METHOD_POST ) ) {
            /** @var string|null $username */
            $username = filter_input( INPUT_POST, 'username' );
            /** @var string|null $email */
            $email = filter_input( INPUT_POST, 'email' );
            /** @var string|null $password */
            $password = filter_input( INPUT_POST, 'password' );
            /** @var string|null $password_repeat */
            $password_repeat = filter_input( INPUT_POST, 'password_repeat' );

            if ( $this->User->register( $username, $email, $password, $password_repeat ) ) {
                $this->httpResponseCode( 201 );
            }
            else {
                $this->httpResponseCode( 400 );
                echo json_encode( Messages::getErrors() );
            }

            exit();
        }

        $this->httpResponseCode( 405 );
        exit();
    }

    /**
     * @access  public
     * @return  void
     */
    public function emailExists() : void {
        if ( $this->isMethod( self::METHOD_GET ) && isset( $_GET[ 'email' ] ) ) {
            /** @var string $email */
            $email = filter_input( INPUT_GET, 'email' );
            /** @var bool $email_exist */
            $email_exists = $this->User->emailExists( $email );

            $this->httpResponseCode( 200 );
            echo json_encode( [ 'email_exists' => $email_exists ] );
            exit();
        }

        $this->httpResponseCode( 400 );
        exit();
    }

    /**
     * @access  public
     * @return  void
     */
    public function usernameExists() : void {
        if ( $this->isMethod( self::METHOD_GET ) && isset( $_GET[ 'username' ] ) ) {
            /** @var string $username */
            $username = filter_input( INPUT_GET, 'username' );
            /** @var bool $username_exists */
            $username_exists = $this->User->usernameExists( $username );

            $this->httpResponseCode( 200 );
            echo json_encode( [ 'username_exists' => $username_exists ] );
            exit();
        }

        $this->httpResponseCode( 400 );
        exit();
    }

}
