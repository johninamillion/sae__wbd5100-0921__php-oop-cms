<?php

namespace CMS\Controller;

use CMS\Controller;

final class Index extends Controller {

    /**
     * Constructor
     * @access  public
     * @constructor
     */
    public function __construct() {
        parent::__construct( TRUE );
    }

    /**
     * Controller index method
     * @access  public
     * @return  void
     */
    public function index(): void {
        $this->redirect( 'posts', 'landing-page' );
    }

}
