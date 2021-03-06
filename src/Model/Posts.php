<?php

namespace CMS\Model;

use CMS\Messages;
use CMS\Model;
use CMS\Session;

final class Posts extends Model {

    /**
     * Validate post message
     * @access  private
     * @param   string|NULL $message
     * @param   string|NULL $error_key
     * @return  bool
     */
    private function validateMessage( ?string $message, ?string $error_key = 'message' ) : bool {
        // Überprüfen ob eine Nachricht eingegeben wurde
        if ( is_null( $message ) === TRUE ) {
            Messages::addError( $error_key, _( 'Please type in a valid message' ) );
        }
        // Überprüfen ob die Nachricht mindestens 8 Zeichen enthält
        if ( strlen( $message ) < 8 ) {
            Messages::addError( $error_key, _( 'The message should be minimum 8 characters long' ) );
        }
        // Überprüfen ob die Nachricht maximal 480 Zeichen enthält
        if ( strlen( $message ) > 480 ) {
            Messages::addError( $error_key, _( 'The message should be maximum 480 characters long' ) );
        }

        return Messages::hasErrors( $error_key ) === FALSE;
    }

    /**
     * Validate post title
     * @access  private
     * @param   string|NULL $title
     * @param   string|NULL $error_key
     * @return  bool
     */
    private function validateTitle( ?string $title, ?string $error_key = 'title' ) : bool {
        // Überprüfen ob ein Titel eingegeben wurde
        if ( is_null( $title ) === TRUE ) {
            Messages::addError( $error_key, _( 'Please type in a valid title' ) );
        }
        // Überprüfen ob der Titel mindestens 8 Zeichen enthält
        if ( strlen( $title ) < 8 ) {
            Messages::addError( $error_key, _( 'The title should be minimum 8 characters long' ) );
        }
        // Überprüfen ob der Titel maximal 40 Zeichen enthält
        if ( strlen( $title ) > 40 ) {
            Messages::addError( $error_key, _( 'The title should be maximum 40 characters long' ) );
        }

        return Messages::hasErrors( $error_key ) === FALSE;
    }

    /**
     * Check if an user is the owner of a post
     * @access  private
     * @param   int     $user_id
     * @param   int     $post_id
     * @return  bool
     */
    public function validateUserPermissions( int $user_id, int $post_id ) : bool {
        /** @var string $query */
        $query = 'SELECT id FROM posts WHERE id = :post_id AND user_id = :user_id';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindParam( ':post_id', $post_id );
        $Statement->bindParam( ':user_id', $user_id );
        $Statement->execute();

        if ( $Statement->rowCount() === 0 ) {
            Messages::addError( 'delete_post', _( 'You dont have the Permissions to delete this Post' ) );
        }

        return Messages::hasErrors( 'delete_post' ) === FALSE;
    }

    /**
     * Create a post
     * @access  public
     * @param   string      $title
     * @param   string      $message
     * @param   int         $created
     * @param   int|NULL    $image_id
     * @return  bool
     */
    public function createPost( string $title, string $message, int $created, ?int $image_id ) : bool {
        /** @var array|NULL $login */
        $login = Session::getValue( 'login' );

        /** @var bool $validate_title */
        $validate_title = $this->validateTitle( $title );
        /** @var bool $validate_message */
        $validate_message = $this->validateMessage( $message );

        if ( $validate_title && $validate_message ) {
            /** @var string $query */
            $query = 'INSERT INTO posts ( user_id, image_id, title, message, created ) VALUES ( :user_id, :image_id, :title, :message, :created )';

            /** @var \PDOStatement $Statement */
            $Statement = $this->Database->prepare( $query );
            $Statement->bindValue( ':user_id', $login[ 'id' ] );
            $Statement->bindValue( ':image_id', $image_id );
            $Statement->bindValue( ':title', $title );
            $Statement->bindValue( ':message', $message );
            $Statement->bindValue( ':created', $created );
            $Statement->execute();

            /** @var bool $success */
            $success = $Statement->rowCount() > 0;

            if ( $success ) {
                Messages::addSuccess( 'create_post', _( 'Your post is published!' ) );
            }

            return $success;
        }

        return FALSE;
    }

    /**
     * Delete post from posts table
     * @access  public
     * @param   int     $post_id
     * @return  bool
     */
    public function deletePost( int $post_id ) : bool {
        /** @var array $login */
        $login = Session::getValue( 'login' );
        /** @var int $user_id */
        $user_id = $login[ 'id' ];

        /** @var bool $validate_user_permissions */
        $validate_user_permissions = $this->validateUserPermissions( $user_id, $post_id );

        if ( $validate_user_permissions ) {
            /** @var string $query */
            $query = 'DELETE i FROM images AS i LEFT JOIN posts AS p ON i.id = p.image_id WHERE p.id = :post_id;'
                   . 'DELETE FROM comments WHERE post_id = :post_id;'
                   . 'DELETE FROM likes WHERE post_id = :post_id;'
                   . 'DELETE FROM posts WHERE id = :post_id AND user_id = :user_id;';

            /** @var \PDOStatement $Statement */
            $Statement = $this->Database->prepare( $query );
            $Statement->bindParam( ':post_id', $post_id );
            $Statement->bindParam( ':user_id', $login[ 'id' ] );
            $Statement->execute();

            /** @var bool $success */
            $success = $Statement->rowCount() > 0;

            if ( $success ) {
                Messages::addSuccess( 'delete_post', _( 'Your post is deleted successfully' ) );
            }

            return $success;
        }

        return FALSE;
    }

    /**
     * Get single post from posts table by post id
     * @access  public
     * @param   int     $post_id
     * @return  array
     */
    public function getPost( int $post_id ) : array {
        /** @var array $login */
        $login = Session::getValue( 'login' );
        /** @var string $user_id */
        $user_id = $login[ 'id' ];

        /** @var string $query */
        $query = 'SELECT p.id AS post_id, u.id AS user_id, u.username AS user_username, p.title AS post_title, p.message AS post_message, p.created AS post_created,'
               . ' i.name as image_name, i.path as image_path, i.thumbnails as image_thumbnails,'
               . ' exists( SELECT 1 FROM likes AS l WHERE l.user_id = :user_id AND l.post_id = p.id LIMIT 1) AS liked,'
               . ' COUNT( l.post_id) AS likes,'
               . ' COUNT( c.post_id ) AS comments'
               . ' FROM posts as p'
               . ' LEFT JOIN users AS u ON p.user_id = u.id'
               . ' LEFT JOIN likes AS l ON p.id = l.post_id'
               . ' LEFT JOIN comments AS c ON p.id = c.post_id'
               . ' LEFT JOIN images AS i ON p.image_id = i.id'
               . ' WHERE p.id = :post_id';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindParam( ':user_id', $user_id );
        $Statement->bindParam( ':post_id', $post_id );
        $Statement->execute();

        return $Statement->fetch() ?? [];
    }

    /**
     * Get posts from posts table
     * @access  public
     * @return  array
     */
    public function getPosts() : array {
        /** @var array $login */
        $login = Session::getValue( 'login' );
        /** @var string $user_id */
        $user_id = $login[ 'id' ];

        /** @var string $query */
        $query = 'SELECT p.id AS post_id, u.id AS user_id, u.username AS user_username, p.title AS post_title, p.message AS post_message, p.created AS post_created,'
               . ' i.name as image_name, i.path as image_path, i.thumbnails as image_thumbnails,'
               . ' exists( SELECT 1 FROM likes AS l WHERE l.user_id = :user_id AND l.post_id = p.id LIMIT 1) AS liked,'
               . ' COUNT( l.post_id ) AS likes,'
               . ' COUNT( c.post_id ) AS comments'
               . ' FROM posts as p'
               . ' LEFT JOIN users AS u ON p.user_id = u.id'
               . ' LEFT JOIN likes AS l ON p.id = l.post_id'
               . ' LEFT JOIN comments AS c ON p.id = c.post_id'
               . ' LEFT JOIN images AS i ON p.image_id = i.id'
               . ' GROUP BY p.id'
               . ' ORDER BY p.created DESC';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindParam( ':user_id', $user_id );
        $Statement->execute();

        return $Statement->fetchAll() ?? [];
    }

    /**
     * Check if a post id exists in posts table
     * @access  public
     * @param   int|null $post_id
     * @return  bool
     */
    public function postIdExists( ?int $post_id ) : bool {
        /** @var string $query */
        $query = 'SELECT id FROM posts WHERE id = :post_id';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindParam( ':post_id', $post_id );
        $Statement->execute();

        return $Statement->rowCount() > 0;
    }

}
