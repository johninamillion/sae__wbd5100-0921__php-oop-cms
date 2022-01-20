<?php

namespace CMS\Controller;

use CMS\Controller;
use CMS\Model\User as UserModel;

final class User extends Controller {

    private ?UserModel $User = NULL;

    /**
     * @access  public
     * @construct
     */
    public function __construct() {
        $this->User = new UserModel();

        parent::__construct( TRUE );
    }

    /**
     * Controller index method
     * @access  public
     * @return  void
     */
    public function index(): void {
        echo "Hello User!";
    }

    /**
     * Controller settings method
     * @access  public
     * @return  void
     */
    public function settings() : void {
        if ( $this->isMethod( self::METHOD_POST ) ) {
            switch( TRUE ) {

                // Neuen Nutzernamen festlegen
                case isset( $_POST[ 'update_username' ] ):
                    $this->User->updateUsername();
                    break;

                // Neue E-Mail Adresse festlegen
                case isset( $_POST[ 'update_email' ] ):
                    $this->User->updateEmail();
                    break;

                // Neues Passwort festlegen
                case isset( $_POST[ 'update_password' ] ):
                    $this->User->updatePassword();
                    break;

                // Löschen des Benutzerkontos
                case isset( $_POST[ 'delete' ] ):
                    if ( $this->User->delete() ) {
                        $this->redirect( '/register?redirected=delete_user' );
                    }
                    break;

            }
        }

        // Titel setzen
        $this->View->Document->setTitle( _( 'User Settings' ) );
        // Stylesheet einbinden
        $this->View->Stylesheets->addStylesheet( 'login', '/assets/dist/css/user' );

        // Template zusammenbauen
        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'navigation' );
        $this->View->getTemplatePart( 'user/settings' );
        $this->View->getTemplatePart( 'footer' );
    }

    /**
     * Controller profile method
     * @access  public
     * @param   string|NULL $username
     * @return  void
     */
    public function profile( ?string $username = NULL ) : void {
        // Titel setzen
        $this->View->Document->setTitle( _( 'User Profile' ) );
        // Stylesheet einbinden
        $this->View->Stylesheets->addStylesheet( 'login', '/assets/dist/css/user' );

        // Template zusammenbauen
        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'navigation' );
        $this->View->getTemplatePart( 'footer' );
    }

}
