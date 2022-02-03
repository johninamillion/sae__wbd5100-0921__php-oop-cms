<?php

namespace CMS\Controller;

use CMS\Controller;

use CMS\Model\Comments as CommentsModel;
use CMS\Model\Images as ImagesModel;
use CMS\Model\Likes as LikesModel;
use CMS\Model\Posts as PostsModel;

final class Posts extends Controller {

    private ?CommentsModel $Comments = NULL;

    private ?ImagesModel $Images = NULL;

    private ?LikesModel $Likes = NULL;

    private ?PostsModel $Posts = NULL;

    /**
     * Constructor
     * @access  public
     * @constructor
     */
    public function __construct() {
        $this->Comments = new CommentsModel();
        $this->Images = new ImagesModel();
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
                    /** @var ?string $title */
                    $title = filter_input( INPUT_POST, 'title' );
                    /** @var ?string $message */
                    $message = filter_input( INPUT_POST, 'message' );#
                    /** @var int $created */
                    $created = $_SERVER[ 'REQUEST_TIME' ] ?? time();
                    /** @var ?int $image_id */
                    $image_id = $this->Images->uploadImage( 'image' );

                    $this->Posts->createPost( $title, $message, $created, $image_id );
                    break;

                case isset( $_POST[ 'delete_post' ] ):
                    /** @var ?string $post_id */
                    $post_id = filter_input( INPUT_POST, 'post_id' );
                    /** @var bool $delete_image */
                    $delete_image = $this->Images->deleteImageByPostId( $post_id );

                    $this->Posts->deletePost( $post_id );
                    break;

                case isset( $_POST[ 'like_post' ] ):
                    /** @var ?string $post_id */
                    $post_id = filter_input( INPUT_POST, 'post_id' );

                    $this->Likes->likePost( $post_id );
                    break;

                case isset( $_POST[ 'unlike_post' ] ):
                    /** @var ?string $post_id */
                    $post_id = filter_input( INPUT_POST, 'post_id' );

                    $this->Likes->unlikePost( $post_id );
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

        if ( $this->isMethod( self::METHOD_POST ) ) {
            switch( TRUE ) {

                case isset( $_POST[ 'create_comment' ] ):
                    /** @var ?string $post_id */
                    $post_id = filter_input( INPUT_POST, 'post_id' );
                    /** @var ?string $comment */
                    $comment = filter_input( INPUT_POST, 'comment' );
                    /** @var int $created */
                    $created = $_SERVER[ 'REQUEST_TIME' ] ?? time();

                    $this->Comments->createComment( $post_id, $comment, $created );
                    break;

                case isset( $_POST[ 'delete_comment' ] ):
                    /** @var ?string $comment_id */
                    $comment_id = filter_input( INPUT_POST, 'comment_id' );

                    $this->Comments->deleteComment( $comment_id );
                    break;

            }
        }

        /** @var array $post */
        $post = $this->Posts->getPost( $post_id );
        /** @var array $comments */
        $comments = $this->Comments->getCommentsByPostId( $post_id );

        $this->View->Data->post = $post;
        $this->View->Data->comments = $comments;

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
