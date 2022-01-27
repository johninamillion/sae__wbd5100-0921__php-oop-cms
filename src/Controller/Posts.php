<?php

namespace CMS\Controller;

use CMS\Controller;

use CMS\Model\Posts as PostsModel;
use CMS\Model\Likes as LikesModel;

final class Posts extends Controller {

    private ?PostsModel $Posts = NULL;

    private ?LikesModel $Likes = NULL;

    /**
     * Constructor
     * @access  public
     * @constructor
     */
    public function __construct() {
        $this->Likes = new LikesModel();
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

                case isset( $_POST[ 'delete_post' ] ):
                    $this->Posts->deletePost();
                    break;

                case isset( $_POST[ 'like_post' ] ):
                    $this->Likes->likePost();
                    break;

            }
        }

        /** @var array $posts */
        $posts = $this->Posts->getPosts();

        $this->View->Data->posts = $posts;

        // Titel setzen
        $this->View->Document->setTitle( _( 'Posts' ) );
        // Stylesheet einbinden
        $this->View->Stylesheets->addStylesheet( 'posts', '/assets/dist/css/posts' );

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

        // Titel setzen
        $this->View->Document->setTitle( $post[ 'post_title' ] );
        // Stylesheet einbinden
        $this->View->Stylesheets->addStylesheet( 'posts', '/assets/dist/css/posts' );

        $this->View->getTemplatePart( 'header' );
        $this->View->getTemplatePart( 'navigation' );
        $this->View->getTemplatePart( 'posts/view' );
        $this->View->getTemplatePart( 'footer' );
    }

}
