<?php

namespace CMS;

abstract class Controller {

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    protected ?View $View = NULL;

    /**
     * Authorize current user login
     * @access  protected
     * @return  void
     */
    protected function authorizeUser() : void {
        if ( Session::hasValue( 'login' ) === FALSE ) {
            $this->redirect( '/login' );
        }
    }

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
     * Check if the server request method is equal
     * @access  protected
     * @param   string  $method
     * @return  bool
     */
    protected function isMethod( string $method ) : bool {

        return $_SERVER[ 'REQUEST_METHOD' ] === $method;
    }

    /**
     * Redirect user
     * @access  protected
     * @param   string  $location
     * @return  void
     */
    protected function redirect( string $location, ?string $redirected = NULL ) : void {
        /** @var string $get */
        $get = $redirected !== NULL ? "?redirected={$redirected}" : "";

        header( "Location: {$location}{$get}" );
    }

    /**
     * @access  public
     * @constructor
     */
    public function __construct( bool $login_required = FALSE ) {
        if ( $login_required === TRUE ) {
            $this->authorizeUser();
        }

        $this->View = new View();
    }

    /**
     * Controller index method
     * @access  public
     * @return  void
     */
    abstract public function index() : void;

}
