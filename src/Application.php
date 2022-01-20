<?php

namespace CMS;

final class Application {

    const INDEX_CONTROLLER = 'index';
    const ERROR_CONTROLLER = 'error';
    const DEFAULT_METHOD = 'index';

    private ?array $url = NULL;

    private function callControllerMethod() : void {
        /** @var string $controller */
        $controller = $this->url[ 'controller' ];
        /** @var string $method */
        $method = $this->url[ 'method' ];
        /** @var string $argument */
        $argument = $this->url[ 'argument' ];

        ( new $controller() )->{$method}();
    }

    /**
     * Add namespace and use uppercase first for to classname
     * @access  private
     * @param   string|NULL $controller
     * @return  bool
     */
    private function controllerClass( ?string $controller ) : string {

        return "CMS\\Controller\\" . ucfirst( $controller ?? self::INDEX_CONTROLLER );
    }

    /**
     * Check if the controller class exists
     * @access  private
     * @param   string|NULL $controller
     * @return  bool
     */
    private function controllerExists( ?string $controller ) : bool {

        return class_exists( $controller );
    }

    /**
     * Check if the controller method exists
     * @access  private
     * @param   string|NULL $controller
     * @param   string|NULL $method
     * @return  bool
     */
    private function controllerMethodExists( ?string $controller, ?string $method ) : bool {

        return method_exists( $controller, $method );
    }

    /**
     * Parse URL
     * @access  private
     * @return  array
     */
    private function parseUrl() : array {
        /** @var string $get */
        $get = $_GET[ '_url' ] ?? '';
        /** @var array $url_parts */
        $url_parts = explode( '/', $get );

        /** @var ?string $controller */
        $controller = $url_parts[ 0 ] != '' ? $url_parts[ 0 ] : NULL;
        /** @var ?string $method */
        $method = $url_parts[ 1 ] ?? NULL;
        /** @var ?string $argument */
        $argument = $url_parts[ 2 ] ?? NULL;

        return [
            'controller'    =>  $this->sanitizeController( $controller ),
            'method'        =>  $this->sanitizeControllerMethod( $controller, $method ),
            'argument'      =>  $argument
        ];
    }

    /**
     * Sanitize controller class name and check if a controller exists, return error controller if not
     * @access  private
     * @param   string|NULL $controller
     * @return  string
     */
    private function sanitizeController( ?string $controller ) : string {
        /** @var string $class */
        $class = $this->controllerClass( $controller );
        /** @var string $error */
        $error = $this->controllerClass( self::ERROR_CONTROLLER );

        return $this->controllerExists( $class ) ? $class : $error;
    }

    /**
     * Sanitize method name and check if the method exists for a controller, return default method if not
     * @access  private
     * @param   string|NULL $controller
     * @param   string|NULL $method
     * @return  string
     */
    private function sanitizeControllerMethod( ?string $controller, ?string $method ) : string {
        /** @var string $class */
        $class = $this->controllerClass( $controller );
        /** @var string $default */
        $default = self::DEFAULT_METHOD;

        return $this->controllerMethodExists( $class, $method ) ? $method : $default;
    }

    /**
     * @access  public
     * @constructor
     */
    public function __construct() {
        // Session starten
        Session::start();
        // Überprüfen ob der Nutze zulange inaktiv war
        Session::loginTimeout();
        // URL auslesen
        $this->url = $this->parseUrl();
    }

    /**
     * Run application
     * @access  public
     * @return  void
     */
    public function run() : void {
        $this->callControllerMethod();
    }

}
