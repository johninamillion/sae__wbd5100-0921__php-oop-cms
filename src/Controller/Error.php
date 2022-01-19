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
        // Status code setzen
        $this->httpResponseCode( $status );

        // Templates einbinden
        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( "error/$status" );
        $this->View->getTemplatePart( 'footer' );
    }

}
