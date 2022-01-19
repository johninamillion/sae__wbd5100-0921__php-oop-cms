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
            $this->User->register();
        }

        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'register/index' );
        $this->View->getTemplatePart( 'footer' );
    }

}
