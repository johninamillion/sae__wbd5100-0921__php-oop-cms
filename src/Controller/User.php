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
        // Überprüfen ob der Nutzer das Formular zum erneuern seines Passworts abgeschickt hat
        if ( $this->isMethod( self::METHOD_POST ) && isset( $_POST[ 'update_password' ] ) && $this->User->updatePassword() ) {
            // nothing here now!
        }
        // Überprüfen ob der Nutzer das Formular zum löschen seinen Accounts abgeschickt hat
        if ( $this->isMethod( self::METHOD_POST ) && isset( $_POST[ 'delete' ] ) && $this->User->delete() ) {
            $this->redirect( '/register' );
        }

        $this->View->getTemplatePart( 'header' );
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
        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'footer' );
    }

}
