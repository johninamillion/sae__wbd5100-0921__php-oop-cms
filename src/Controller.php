<?php

namespace CMS;

abstract class Controller {

    private ?View $View = NULL;

    /**
     * @access  public
     * @constructor
     */
    public function __construct() {
        $this->View = new View();
    }

    /**
     * Controller index method
     * @access  public
     * @return  void
     */
    abstract public function index() : void;

}
