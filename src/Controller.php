<?php

namespace CMS;

abstract class Controller {

    protected ?View $View = NULL;

    /**
     * Set http response code
     * @access  protected
     * @param   int     $code
     * @return  void
     */
    protected function httpResponseCode( int $code = 200 ) : void {
        http_response_code( $code );
    }

    /**
     * Redirect user
     * @access  protected
     * @param   string  $location
     * @return  void
     */
    protected function redirect( string $location ) : void {
        header( "Location: $location" );
    }

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
