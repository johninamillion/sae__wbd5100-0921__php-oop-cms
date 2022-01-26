<?php

namespace CMS\Controller;

use CMS\Controller;

use CMS\Model\Posts as PostsModel;

final class Posts extends Controller {

    private ?PostsModel $Posts = NULL;

    /**
     * Constructor
     * @access  public
     * @constructor
     */
    public function __construct() {
        $this->Posts = new PostsModel();

        parent::__construct( TRUE );
    }

    /**
     * Posts index method.
     * @access  public
     * @return  void
     */
    public function index() : void {
        if ( $this->isMethod( self::METHOD_POST ) ) {
            switch( TRUE ) {

                case isset( $_POST[ 'create_post' ] ):
                    $this->Posts->createPost();
                    break;

            }
        }

        $this->View->Data->posts = $this->Posts->getPosts();

        $this->View->Document->setTitle( _( 'Posts' ) );

        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'navigation' );
        $this->View->getTemplatePart( 'posts/index' );
        $this->View->getTemplatePart( 'footer' );
    }

}
