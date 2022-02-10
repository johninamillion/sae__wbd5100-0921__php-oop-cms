<?php

namespace CMS\Controller;

use CMS\Controller;
use CMS\Model\User as UserModel;

final class Verification extends Controller {

    private ?UserModel $User = NULL;

    /**
     * @access  public
     * @return  void
     */
    public function __construct() {
        $this->User = new UserModel();

        parent::__construct( FALSE );
    }

    /**
     * @access  public
     * @return  void
     */
    public function index(): void {
        if ( $this->isMethod( self::METHOD_GET ) === FALSE || isset( $_GET[ 'id' ] ) === FALSE ) {
            Error::init();
        }
        /** @var string $verification_id */
        $verification_id = $_GET[ 'id' ];

        if ( $this->User->verifiyUser( $verification_id ) ) {
            $this->redirect( '/login', 'verification' );
        }
    }

}