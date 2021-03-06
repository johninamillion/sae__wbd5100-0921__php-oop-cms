<?php

namespace CMS\Model;

use CMS\Messages;
use CMS\Model;
use CMS\Session;

final class Comments extends Model {

    /**
     * Create a comment in comments table
     * @access  public
     * @param   int     $post_id
     * @param   string  $comment
     * @param   int     $created
     * @return  bool
     */
    public function createComment( int $post_id, string $comment, int $created ) : bool {
        /** @var array $login */
        $login = Session::getValue( 'login' );
        /** @var string $user_id */
        $user_id = $login[ 'id' ];

        // TODO: validate comment
        if ( TRUE ) {
            /** @var string $query */
            $query = 'INSERT INTO comments ( user_id, post_id, comment, created ) VALUES ( :user_id, :post_id, :comment, :created );';

            /** @var \PDOStatement $Statement */
            $Statement = $this->Database->prepare( $query );
            $Statement->bindValue( ':user_id', $user_id );
            $Statement->bindValue( ':post_id', $post_id );
            $Statement->bindValue( ':comment', $comment );
            $Statement->bindValue( ':created', $created );
            $Statement->execute();

            $success = $Statement->rowCount() > 0;

            if ( $success ) {
                Messages::addSuccess( 'create_comment', _( 'Your comment is published' ) );
            }

            return $success;
        }

        return FALSE;
    }

    /**
     * Delete Comment
     * @access  public
     * @param   int     $comment_id
     * @return  bool
     */
    public function deleteComment( int $comment_id ) : bool {
        /** @var array $login */
        $login = Session::getValue( 'login' );
        /** @var int $user_id */
        $user_id = $login[ 'id' ];

        // TODO: validate action
        if( TRUE ) {
            /** @var string $query */
            $query = 'DELETE FROM comments WHERE id = :comment_id';

            /** @var \PDOStatement $Statement */
            $Statement = $this->Database->prepare( $query );
            $Statement->bindParam( ':comment_id', $comment_id );
            $Statement->execute();

            /** @var bool $success */
            $success = $Statement->rowCount() > 0;

            if ( $success ) {
                Messages::addSuccess( 'delete_comment', 'Your comment is deleted' );
            }

            return $success;
        }

        return FALSE;
    }

    /**
     * Get comments by post id
     * @access  public
     * @param   int     $post_id
     * @return  array
     */
    public function getCommentsByPostId( int $post_id ) : array {
        /** @var string $query */
        $query = 'SELECT c.id AS comment_id, c.comment AS comment, c.created AS created, u.id AS user_id, u.username AS user_username'
               . ' FROM comments AS c'
               . ' LEFT JOIN users AS u ON c.user_id = u.id'
               . ' WHERE c.post_id = :post_id';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindParam( ':post_id', $post_id );
        $Statement->execute();

        return $Statement->fetchAll();
    }

}
