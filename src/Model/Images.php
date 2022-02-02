<?php

namespace CMS\Model;

use CMS\Messages;
use CMS\Model;

final class Images extends Model {

    /**
     * @access  private
     * @return  string
     */
    private function createDateCodedPath() : string {
        /** @var string $date */
        $date = date( 'Y.m.d', time() );
        /** @var array $code */
        $code = explode( '.', $date );
        /** @var  $path */
        $path = implode( DIRECTORY_SEPARATOR, $code );

        return $path;
    }

    /**
     * @access  public
     * @param   string $directory
     * @return  bool
     */
    private function createFolder( string $directory ) : bool {
        if ( file_exists( $directory ) === FALSE ) {

            return (bool) mkdir( $directory, 0777, TRUE );
        }

        return TRUE;
    }

    /**
     * @access  private
     * @param   string  $image_path
     * @param   string  $thumbnail_name
     * @param   string  $thumbnail_path
     * @param   int     $thumbnail_width
     * @param   int     $thumbnail_height
     * @return  array|NULL
     */
    private function createThumbnail(
        string $image_path,
        string $thumbnail_name,
        string $thumbnail_path,
        string $relative_thumbnail_path,
        int $thumbnail_width,
        int $thumbnail_height
    ) : ?array {
        list( $image_width, $image_height, $image_type ) = getimagesize( $image_path );

        switch( $image_type ) {
            case IMAGETYPE_JPEG:
                /** @var \GdImage $gd_image */
                $gd_image = imagecreatefromjpeg( $image_path );
                break;
            case IMAGETYPE_PNG:
                /** @var \GdImage $gd_image */
                $gd_image = imagecreatefrompng( $image_path );
                break;
            default:

                return NULL;
        }

        /** @var int|float $image_ratio */
        $image_ratio = $image_width / $image_height;
        /** @var int|float $thumbnail_ratio */
        $thumbnail_ratio = $thumbnail_width / $thumbnail_height;

        if ( $thumbnail_ratio > $image_ratio ) {
            $thumbnail_width = $thumbnail_height * $image_ratio;
        }
        else {
            $thumbnail_height = $thumbnail_width / $image_ratio;
        }

        /** @var \GdImage $gd_thumbnail */
        $gd_thumbnail = imagecreatetruecolor( $thumbnail_width, $thumbnail_height );

        imagecopyresampled( $gd_thumbnail, $gd_image, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $image_width, $image_height );

        switch( $image_type ) {
            case IMAGETYPE_JPEG:
                imagejpeg( $gd_thumbnail, $thumbnail_path, 90 );
                break;
            case IMAGETYPE_PNG:
                imagepng( $gd_thumbnail, $thumbnail_path );
                break;
        }

        imagedestroy( $gd_image );
        imagedestroy( $gd_thumbnail );

        return [
            'name'  =>  $thumbnail_name,
            'path'  =>  $relative_thumbnail_path
        ];
    }

    /**
     * @access  private
     * @param   string $name
     * @param   string $path
     * @param   string $thumbnails
     * @param   string $mime_type
     * @return  int|NULL
     */
    private function insertImage( string $name, string $path, string $thumbnails, string $mime_type ) : ?int {
        /** @var string $query */
        $query = 'INSERT INTO images ( name, path, thumbnails, mime_type ) VALUES ( :name, :path, :thumbnails, :mime_type );';

        /** @var \PDOStatement $Statement */
        $Statement = $this->Database->prepare( $query );
        $Statement->bindValue( ':name', $name );
        $Statement->bindValue( ':path', $path );
        $Statement->bindValue( ':thumbnails', $thumbnails );
        $Statement->bindValue( ':mime_type', $mime_type );
        $Statement->execute();

        return $Statement->rowCount() > 0 ? $this->Database->lastInsertId() : NULL;
    }

    /**
     * @access  private
     * @param   string  $temp_image_file
     * @param   string  $target_image_path
     * @return  bool
     */
    private function moveFile( string $temp_image_file, string $target_image_path ) : bool {

        return (bool) move_uploaded_file( $temp_image_file, $target_image_path );
    }

    /**
     * @param   string  $image_type
     * @return  string|NULL
     */
    private function sanitizeFileExtension( string $image_type ) : ?string {
        switch( $image_type ) {
            case IMAGETYPE_JPEG:
                return '.jpeg';
            case IMAGETYPE_PNG:
                return '.png';
            default:
                return NULL;
        }
    }

    /**
     * @access  private
     * @param   array   $thumbnails
     * @return  string
     */
    private function serializeThumbnails( array $thumbnails ) : string {

        return serialize( $thumbnails );
    }

    /**
     * @access  private
     * @param string $image_size
     * @return bool
     */
    private function validateImageSize( string $image_size ) : bool {
        /** @var string $ini_max_file_upload */
        $max_file_uploads = ini_get( 'max_file_uploads' );
        /** @var int $max_file_size_bytes */
        $max_file_size_bytes = (int) str_replace( 'M', '000000', $max_file_uploads );

        if ( $image_size > $max_file_size_bytes ) {
            Messages::addError( 'image', _( 'You file should not be bigger than the max file upload size' ) );
        }

        return Messages::hasErrors( 'image' ) === FALSE;
    }

    /**
     * @access  private
     * @param   string  $image_type
     * @return  bool
     */
    private function validateImageType( string $image_type ) : bool {
        if ( in_array( $image_type, [ IMAGETYPE_JPEG, IMAGETYPE_PNG ] ) === FALSE ) {
            Messages::addError( 'image', _( 'Please use a valid image type.' ) );
        }

        return Messages::hasErrors( 'image' ) === FALSE;
    }

    /**
     * @access  public
     * @param   string      $input_name
     * @param   string      $upload_path
     * @param   array|NULL  $thumbnails
     * @return  int|NULL
     */
    public function uploadImage(
        string $input_name,
        ?array $thumbnails = [
            'thumbnail' =>  [ 200, 200 ],
            'hd'        =>  [ 1280, 720 ],
            'full-hd'   =>  [ 1920, 1080 ]
        ],
        string $upload_path = APPLICATION_UPLOAD_DIR
    ) : ?int {
        // Überprüfen ob ein File vom Nutzer abgeschickt wurde
        if ( isset( $_FILES[ $input_name ] ) === FALSE ) {

            return NULL;
        }

        /** @var string $temp_image_id */
        $temp_image_id = $_FILES[ $input_name ][ 'name' ];
        /** @var string $temp_image_file */
        $temp_image_file = $_FILES[ $input_name ][ 'tmp_name' ];
        /** @var string $temp_image_mime_type */
        $temp_image_mime_type = $_FILES[ $input_name ][ 'type' ];
        /** @var int $temp_image_size */
        $temp_image_size = $_FILES[ $input_name ][ 'size' ];

        list( $image_width, $image_height, $image_type ) = getimagesize( $temp_image_file );

        /** @var bool $validate_image_type */
        $validate_image_type = $this->validateImageType( $image_type );
        /** @var bool $validate_image_size */
        $validate_image_size = $this->validateImageSize( $temp_image_size );

        // File Upload verlassen, wenn der Dateityp nicht valide ist oder die Datei zu Groß ist
        // @TODO validate image size
        if ( $validate_image_type === FALSE ) {

            return NULL;
        }

        /** @var array $temp_image_arr */
        $temp_image_arr = @explode( '.', $temp_image_id );
        /** @var string $temp_file_ext */
        $temp_file_ext = '.' . $temp_image_arr[ count( $temp_image_arr ) - 1 ];
        /** @var string $temp_image_name */
        $temp_image_name = str_replace( $temp_file_ext, '', $temp_image_id );
        /** @var string $image_name */
        $image_name = sprintf(
            '%1$s-%2$s-%3$s',
            $temp_image_name,
            time(),
            rand( 1234, 9876 )
        );
        /** @var string $date_coded_path */
        $date_coded_path = $this->createDateCodedPath();
        /** @var bool $file_ext */
        $file_ext = $this->sanitizeFileExtension( $image_type );
        /** @var string $target_dir */
        $target_dir = $upload_path . DIRECTORY_SEPARATOR . $date_coded_path;
        /** @var string $target_image_path */
        $target_image_path = $target_dir . DIRECTORY_SEPARATOR . $image_name . $file_ext;
        /** @var string $relative_image_path */
        $relative_image_path = $date_coded_path . DIRECTORY_SEPARATOR . $image_name . $file_ext;

        if ( $this->createFolder( $target_dir ) === FALSE ) {
            Messages::addError( 'image', _( 'Can\'t create directory for file upload' ) );

            return NULL;
        }

        if ( $this->moveFile( $temp_image_file, $target_image_path ) === FALSE ) {
            Messages::addError( 'image', _( 'Can\'t move the file' ) );

            return NULL;
        }

        /** @var array $thumbnails_arr */
        $thumbnails_arr = [];

        if ( is_null( $thumbnails ) === FALSE ) {
            foreach ( $thumbnails as $key => $dimensions ) {
                /** @var string $thumbnail_name */
                $thumbnail_name =  "$temp_image_name-$key";
                /** @var string $thumbnail_path */
                $thumbnail_path = $target_dir . DIRECTORY_SEPARATOR . $thumbnail_name . $file_ext;
                /** @var string $relative_thumbnail_path */
                $relative_thumbnail_path = $date_coded_path . DIRECTORY_SEPARATOR . $thumbnail_name . $file_ext;
                /** @var int $thumbnail_width */
                $thumbnail_width = $dimensions[ 0 ];
                /** @var int $thumbnail_height */
                $thumbnail_height = $dimensions[ 1 ];

                $thumbnails_arr[ $key ] = $this->createThumbnail( $target_image_path, $thumbnail_name, $thumbnail_path, $relative_thumbnail_path, $thumbnail_width, $thumbnail_height );
            }
        }

        /** @var string $thumbnails */
        $thumbnails = $this->serializeThumbnails( $thumbnails_arr );

        return $this->insertImage( $temp_image_name, $relative_image_path, $thumbnails, $temp_image_mime_type );
    }

}