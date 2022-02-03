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
                    /** @var ?string $new_username */
                    $new_username = filter_input( INPUT_POST, 'new_username' );

                    $this->User->updateUsername( $new_username );
                    break;

                // Neue E-Mail Adresse festlegen
                case isset( $_POST[ 'update_email' ] ):
                    /** @var ?string $new_email */
                    $new_email = filter_input( INPUT_POST, 'new_email' );

                    $this->User->updateEmail( $new_email );
                    break;

                // Neues Passwort festlegen
                case isset( $_POST[ 'update_password' ] ):
                    /** @var ?string $password */
                    $password = filter_input( INPUT_POST, 'password' );
                    /** @var ?string $new_password */
                    $new_password = filter_input( INPUT_POST, 'new_password' );
                    /** @var ?string $new_password_repeat */
                    $new_password_repeat = filter_input( INPUT_POST, 'new_password_repeat' );

                    $this->User->updatePassword( $password, $new_password, $new_password_repeat );
                    break;

                // Löschen des Benutzerkontos
                case isset( $_POST[ 'delete' ] ):
                    /** @var ?string $password */
                    $password = filter_input( INPUT_POST , 'password' );
                    /** @var bool $delete_user_image */
                    $delete_user_image = $this->Images->deleteImageByUserId( $_SESSION[ 'login' ][ 'id' ] );
                    /** @var bool $delete_post_images */
                    $delete_post_images = $this->Images->deletePostImagesByUserId( $_SESSION[ 'login' ][ 'id' ] );

                    if ( $this->User->delete( $password ) && $this->User->logout() ) {
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
        $this->View->Data->username = $username;

        // Titel setzen
        $this->View->Document->setTitle( sprintf( _( 'User Profile of %s' ), $username ) );
        // Stylesheet einbinden
        $this->View->Stylesheets->addStylesheet( 'user', '/assets/dist/css/user' );

        // Template zusammenbauen
        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'navigation' );
        $this->View->getTemplatePart( 'user/profile' );
        $this->View->getTemplatePart( 'footer' );
    }

}
