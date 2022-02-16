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
        $this->contentType( self::CONTENT_JSON );

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
     * @param   int|null    $user_id
     * @return  void
     */
    public function deleteUser( ?int $user_id ) : void {
        if ( $this->isMethod( self::METHOD_DELETE ) && $user_id !== NULL ) {
            /** @var array $form_data */
            $form_data = $this->getFormData();
            /** @var string $password */
            $password = $form_data[ 'password' ];
            /** @var bool $delete_user */
            $delete_user = $this->User->deleteUserById( $user_id, $password );

            $this->httpResponseCode( 200 );
            echo json_encode( [ 'delete_user' => $delete_user ] );
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
    public function registerUser() : void {
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
