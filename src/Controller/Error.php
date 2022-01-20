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
        /** @var string $title */
        $title = sprintf(
            _( 'Error %1$s' ),
            $status
        );

        // Status code setzen
        $this->httpResponseCode( $status );

        // Titel setzen
        $this->View->Document->setTitle( $title );

        // Template zusammenbauen
        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( "error/$status" );
        $this->View->getTemplatePart( 'footer' );
    }

}
