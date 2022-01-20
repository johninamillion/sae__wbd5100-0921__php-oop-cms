<?php

namespace CMS\Controller;

use CMS\Controller;
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
        if ( $this->isMethod( self::METHOD_POST ) && $this->User->login() ) {
            $this->redirect( '/' );
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

}
