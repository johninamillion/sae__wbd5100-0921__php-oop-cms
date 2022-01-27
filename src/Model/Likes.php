<?php

namespace CMS\Model;

use CMS\Messages;
use CMS\Model;
use CMS\Session;

final class Likes extends Model {

    /**
     * Check if the user liked the post already
     * @param int $user_id
     * @param int $post_id
     * @return bool
     */
    private function isPostLikedByUser( int $user_id, int $post_id ) : bool {
        /** @var string $query */
        $query = 'SELECT user_id, post_id FROM likes WHERE user_id = :user_id AND post_id = :post_id';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindParam( ':user_id', $user_id );
        $Statement->bindParam( ':post_id', $post_id );
        $Statement->execute();

        return $Statement->rowCount() > 0;
    }

    /**
     * Check if the user is the owner of the post
     * @access  private
     * @param   int     $user_id
     * @param   int     $post_id
     * @return  bool
     */
    private function isUserOwnerOfPost( int $user_id, int $post_id ) : bool {
        /** @var string $query */
        $query = 'SELECT id FROM posts WHERE id = :post_id AND user_id = :user_id';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindParam( ':post_id', $post_id );
        $Statement->bindParam( ':user_id', $user_id );
        $Statement->execute();

        return $Statement->rowCount() > 0;
    }

    /**
     * Check if the post id exists in posts table
     * @access  private
     * @param   int     $post_id
     * @return  bool
     */
    private function postIdExists( int $post_id ) : bool {
        /** @var string $query */
        $query = 'SELECT id FROM posts WHERE id = :post_id';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindParam( ':post_id', $post_id );
        $Statement->execute();

        return $Statement->rowCount() > 0;
    }

    /**
     * Validate user permission to like the post
     * @access  private
     * @param   int     $user_id
     * @param   int     $post_id
     * @return  bool
     */
    private function validatePermissions( int $user_id, int $post_id ) : bool {
        // Überprüfen ob der Beitrag existiert
        if ( $this->postIdExists( $post_id ) === FALSE ) {
            Messages::addError( 'like_post', _( 'The Post doesn\'t exist' ) );
        }
        // Überprüfen ob der Nutzer der Eigentümer des Beitrags ist
        if ( $this->isUserOwnerOfPost( $user_id, $post_id ) === TRUE ) {
            Messages::addError( 'like_post', _( 'As the owner of the post you are not allowed to like the post' ) );
        }

        return Messages::hasErrors( 'like_post' ) === FALSE;
    }

    /**
     * Like a post
     * @access  public
     * @return  bool
     */
    public function likePost() : bool {
        /** @var array $login */
        $login = Session::getValue( 'login' );
        /** @var string $user_id */
        $user_id = $login[ 'id' ];
        /** @var ?string $post_id */
        $post_id = filter_input( INPUT_POST, 'post_id' );

        /** @var bool $validate_permissions */
        $validate_permissions = $this->validatePermissions( $user_id, $post_id );
        /** @var bool $validate_like */
        $validate_like = $this->isPostLikedByUser( $user_id, $post_id );

        if ( $validate_permissions && $validate_like === FALSE ) {
            /** @var string $query */
            $query = 'INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)';

            /** @var \PDOStatement $Statement */
            $Statement = $this->Database->prepare( $query );
            $Statement->bindValue( ':user_id', $user_id );
            $Statement->bindValue( ':post_id', $post_id );
            $Statement->execute();

            $success = $Statement->rowCount() > 0;

            if ( $success ) {
                Messages::addSuccess( 'like_post', _( 'You liked the post' ) );
            }

            return $success;
        }

        return FALSE;
    }

    /**
     * Unlike a post
     * @access  public
     * @return  bool
     */
    public function unlikePost() : bool {
        /** @var array $login */
        $login = Session::getValue( 'login' );
        /** @var string $user_id */
        $user_id = $login[ 'id' ];
        /** @var ?string $post_id */
        $post_id = filter_input( INPUT_POST, 'post_id' );

        /** @var bool $validate_permissions */
        $validate_permissions = $this->validatePermissions( $user_id, $post_id );
        /** @var bool $validate_like */
        $validate_like = $this->isPostLikedByUser( $user_id, $post_id );

        if ( $validate_permissions && $validate_like === TRUE ) {
            /** @var string $query */
            $query = 'DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id;';

            /** @var \PDOStatement $Statement */
            $Statement = $this->Database->prepare( $query );
            $Statement->bindParam( ':user_id', $user_id );
            $Statement->bindParam( ':post_id', $post_id );
            $Statement->execute();

            $success = $Statement->rowCount();

            if ( $success ) {
                Messages::addSuccess( 'unlike_post', _( 'You unliked the post' ) );
            }

            return $success;
        }

        return FALSE;
    }

}