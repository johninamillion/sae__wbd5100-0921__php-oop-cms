<?php

namespace CMS;

abstract class Controller {

    const CONTENT_JSON = 'application/json';
    const CONTENT_HTML = 'text/html';

    const METHOD_DELETE = 'DELETE';
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
            $this->redirect( '/login', 'unauthorized' );
        }
    }

    /**
     * @access  protected
     * @param   string  $type
     * @return  void
     */
    protected function contentType( string $type ) : void {
        switch( $type ) {
            case self::CONTENT_JSON:
                header( 'Content-Type: application/json; charset=UTF-8' );
                break;
            case self::CONTENT_HTML:
                header( 'Content-Type: text/html; charset=UTF-8' );
                break;
        }
    }

    public function getInput() : string {

        return file_get_contents('php://input');
    }

    public function getFormData() : array {
        /** @var array $data */
        $data = [];
        // read incoming data
        $input = $this->getInput();

        // grab multipart boundary from content type header
        preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
        $boundary = $matches[1];

        // split content by boundary and get rid of last -- element
        $a_blocks = preg_split("/-+$boundary/", $input);
        array_pop($a_blocks);

        // loop data blocks
        foreach ($a_blocks as $id => $block)
        {
            if (empty($block))
                continue;

            // you'll have to var_dump $block to understand this and maybe replace \n or \r with a visibile char

            // parse uploaded files
            if (strpos($block, 'application/octet-stream') !== FALSE)
            {
                // match "name", then everything after "stream" (optional) except for prepending newlines
                preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
            }
            // parse all other fields
            else
            {
                // match "name" and optional value in between newline sequences
                preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
            }

            $data[$matches[1]] = $matches[2];
        }

        return $data;
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
