<?php

namespace CMS\Controller;

use CMS\Controller;
use CMS\Model\Images as ImagesModel;
use CMS\Model\User as UserModel;

final class User extends Controller {

    private ?ImagesModel $Images = NULL;

    private ?UserModel $User = NULL;

    /**
     * @access  public
     * @construct
     */
    public function __construct() {
        $this->Images = new ImagesModel();
        $this->User = new UserModel();

        parent::__construct( TRUE );
    }

    /**
     * Controller index method
     * @access  public
     * @return  void
     */
    public function index(): void {
        // Titel setzen
        $this->View->Document->setTitle( 'User' );
        // Stylesheet einbinden
        $this->View->Stylesheets->addStylesheet( 'user', '/assets/dist/css/user' );

        // Template zusammenbauen
        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'navigation' );
        $this->View->getTemplatePart( 'footer' );
    }

    /**
     * Controller settings method
     * @access  public
     * @return  void
     */
    public function settings() : void {
        if ( $this->isMethod( self::METHOD_POST ) ) {
            switch( TRUE ) {

                case isset( $_POST[ 'update_avatar' ] ):
                    /** @var int $image_id */
                    $image_id = $this->Images->uploadImage( 'avatar', [ 'avatar' => [ 240, 240 ] ] );

                    $this->User->updateAvatar( $image_id );
                    break;

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
                        $this->redirect( '/register', 'delete_user' );
                    }
                    break;

            }
        }

        // Titel setzen
        $this->View->Document->setTitle( _( 'User Settings' ) );
        // Stylesheet einbinden
        $this->View->Stylesheets->addStylesheet( 'user', '/assets/dist/css/user' );

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
        $this->View->Stylesheets->addStylesheet( 'user', '/assets/dist/css/user' );

        // Template zusammenbauen
        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'navigation' );
        $this->View->getTemplatePart( 'footer' );
    }

}
