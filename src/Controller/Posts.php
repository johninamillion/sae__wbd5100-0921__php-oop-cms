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

        /** @var array $posts */
        $posts = $this->Posts->getPosts();

        $this->View->Data->posts = $posts;

        $this->View->Document->setTitle( _( 'Posts' ) );

        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'navigation' );
        $this->View->getTemplatePart( 'posts/index' );
        $this->View->getTemplatePart( 'footer' );
    }

    /**
     * View single post
     * @access  public
     * @param   string|NULL $post_id
     * @return  void
     */
    public function view( ?string $post_id = NULL ) : void {
        // Überprüfen ob die post id existiert, und einen Error 404 ausspielen, wenn nicht
        if ( $this->Posts->postIdExists( $post_id ) === FALSE ) {
            Error::init();
        }

        /** @var array $post */
        $post = $this->Posts->getPost( $post_id );

        $this->View->Data->post = $post;

        $this->View->Document->setTitle( $post[ 'post_title' ] );

        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'navigation' );
        $this->View->getTemplatePart( 'posts/view' );
        $this->View->getTemplatePart( 'footer' );
    }

}
