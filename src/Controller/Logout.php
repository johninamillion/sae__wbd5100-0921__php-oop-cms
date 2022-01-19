<?php

namespace CMS\Controller;

use CMS\Controller;

final class Login extends Controller {

    /**
     * Controller index method
     * @access  public
     * @return  void
     */
    public function index(): void {
        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'login/index' );
        $this->View->getTemplatePart( 'footer' );
    }

}