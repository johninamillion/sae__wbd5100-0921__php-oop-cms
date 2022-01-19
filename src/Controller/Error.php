<?php

namespace CMS\Controller;

use CMS\Controller;

final class Error extends Controller {

    /**
     * Controller index method
     * @access  public
     * @param   int     $status
     * @return  void
     */
    public function index( int $status = 404 ): void {
        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'footer' );
    }

}
