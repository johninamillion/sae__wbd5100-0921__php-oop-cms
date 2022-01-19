<?php

namespace CMS\Controller;

use CMS\Controller;

final class Register extends Controller {

    /**
     * Controller index method
     * @access  public
     * @return  void
     */
    public function index(): void {
        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'register/index' );
        $this->View->getTemplatePart( 'footer' );
    }

}
