<?php

namespace CMS\Controller;

use CMS\Controller;

final class Logout extends Controller {

    /**
     * Controller index method
     * @access  public
     * @return  void
     */
    public function index(): void {
        $this->redirect( 'login' );
    }

}