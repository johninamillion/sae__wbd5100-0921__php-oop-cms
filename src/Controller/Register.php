<?php

namespace CMS\Controller;

use CMS\Controller;
use CMS\Model\User as UserModel;

final class Register extends Controller {

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
            /** @var ?string $email */
            $email = filter_input( INPUT_POST, 'email' );
            /** @var ?string $password */
            $password = filter_input( INPUT_POST, 'password' );
            /** @var ?string $password_repeat */
            $password_repeat = filter_input( INPUT_POST, 'password_repeat' );

            if ( $this->User->register( $username, $email, $password, $password_repeat ) ) {
                $this->redirect( '/login', 'register' );
            }
        }

        // Titel setzen
        $this->View->Document->setTitle( _( 'Register' ) );
        // Stylesheet einbinden
        $this->View->Stylesheets->addStylesheet( 'register', '/assets/dist/css/register' );

        // Template zusammenbauen
        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'register/index' );
        $this->View->getTemplatePart( 'footer' );
    }

}
