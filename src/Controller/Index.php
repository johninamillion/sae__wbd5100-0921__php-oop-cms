<?php

namespace CMS\Controller;

use CMS\Controller;

final class Index extends Controller {

    /**
     * Controller index method
     * @access  public
     * @return  void
     */
    public function index(): void {
        // Titel setzen
        $this->View->Document->setTitle( 'Homepage' );

        // Template zusammenbauen
        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'navigation' );
        $this->View->getTemplatePart( 'footer' );
    }

}
