<?php

namespace CMS\Controller;

use CMS\Controller;
use CMS\Messages;
use CMS\Model\User as UserModel;

final class Login extends Controller {

    private ?UserModel $User = NULL;

    /**
     * @access  public
     * @constructor
     */
    public function __construct() {
        $this->User = new UserModel();

        parent::__construct();
    }

    /**
     * Controller index method
     * @access  public
     * @return  void
     */
    public function index(): void {
        if ( $this->isMethod( self::METHOD_POST ) ) {
            /** @var ?string $username */
            $username = filter_input( INPUT_POST, 'username' );
            /** @var ?string $password */
            $password = filter_input( INPUT_POST, 'password' );

            if ( $this->User->login( $username, $password ) ) {
                $this->redirect( '/', 'login' );
            }
        }

        // Titel setzen
        $this->View->Document->setTitle( _( 'Login' ) );
        // Einbinden eines Stylesheets
        $this->View->Stylesheets->addStylesheet( 'login', '/assets/dist/css/login' );
        // Einbinden eines JavaScripts
        $this->View->Scripts->addScript( 'login', '/assets/dist/js/login' );

        // Template zusammenbauen
        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'login/index' );
        $this->View->getTemplatePart( 'footer' );
    }

    /**
     * @access  public
     * @return  void
     */
    public function reset() : void {
        if ( $this->isMethod( self::METHOD_POST ) ) {
            switch( TRUE ) {

                case isset( $_POST[ 'request-reset' ] ):
                    /** @var string|NULL $email */
                    $email = filter_input( INPUT_POST, 'email' );

                    if ( $this->User->addPasswordReset( $email ) ) {
                        Messages::addSuccess( 'request-reset', _( 'You have recived an E-Mail with a Link to reset your Password' ) );
                    }
                    break;

                case isset( $_POST[ 'reset-password' ] ) :
                    /** @var string|NULL $reset_id */
                    $reset_id = filter_input( INPUT_POST, 'reset_id' );
                    /** @var string|NULL $password */
                    $password = filter_input( INPUT_POST, 'password' );
                    /** @var string|NULL $password_repeat */
                    $password_repeat = filter_input( INPUT_POST, 'password_repeat' );

                    if ( $this->User->resetPassword( $reset_id, $password, $password_repeat ) ) {
                        $this->redirect( '/login', 'reset' );
                    }

                    break;

            }
        }

        // Titel setzen
        $this->View->Document->setTitle( _( 'Reset Login' ) );
        // Einbinden eines Stylesheets
        $this->View->Stylesheets->addStylesheet( 'login', '/assets/dist/css/login' );
        // Einbinden eines JavaScripts
        $this->View->Scripts->addScript( 'login', '/assets/dist/js/login' );

        // Template zusammenbauen
        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'login/reset' );
        $this->View->getTemplatePart( 'footer' );
    }

}
