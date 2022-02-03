<?php

namespace CMS\Controller;

use CMS\Controller;

use CMS\Model\User as UserModel;

final class Search extends Controller {

    private ?UserModel $User = NULL;

    /**
     * @access  public
     * @constructor
     */
    public function __construct() {
        $this->User = new UserModel();

        parent::__construct( TRUE );
    }

    /**
     * @access  public
     * @return  void
     */
    public function index() : void {
        if ( $this->isMethod( self::METHOD_GET ) === FALSE || isset( $_GET[ 'keyword' ] ) === FALSE ) {
            Error::init();
        }

        /** @var string $keyword */
        $keyword = filter_input( INPUT_GET, 'keyword' );
        /** @var array $entries */
        $entries = $this->User->searchUser( $keyword );

        $this->View->Data->entries = $entries;

        $this->View->Document->setTitle( sprintf( _( 'Search for \'%s\'' ), $keyword ) );
        $this->View->Stylesheets->addStylesheet( 'search', '/assets/dist/css/search' );

        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'navigation' );
        $this->View->getTemplatePart( 'search/index' );
        $this->View->getTemplatePart( 'footer' );
    }

}
