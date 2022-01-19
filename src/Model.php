<?php

namespace CMS;

abstract class Model {

    protected ?Database $Database = NULL;

    /**
     * @access  public
     * @constructor
     */
    public function __construct() {
        $this->Database = new Database();
    }

}
