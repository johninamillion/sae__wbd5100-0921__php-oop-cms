<?php

namespace CMS\Controller;

use CMS\Controller;

final class Posts extends Controller {

    /**
     * Constructor
     * @access  public
     * @constructor
     */
    public function __construct() {
        parent::__construct( TRUE );
    }

    /**
     * Posts index method.
     * @access  public
     * @return  void
     */
    public function index() : void {
        $this->View->Document->setTitle( _( 'Posts' ) );
        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'navigation' );
        $this->View->getTemplatePart( 'footer' );
    }

}