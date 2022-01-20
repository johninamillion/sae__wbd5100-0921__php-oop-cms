<?php

namespace CMS\Controller;

use CMS\Controller;

final class User extends Controller {

    /**
     * Controller index method
     * @access  public
     * @return  void
     */
    public function index(): void {
        echo "Hello User!";
    }

    /**
     * Controller settings method
     * @access  public
     * @return  void
     */
    public function settings() : void {
        echo "User Settings";
    }

    /**
     * Controller profile method
     * @access  public
     * @param   string|NULL $username
     * @return  void
     */
    public function profile( ?string $username = NULL ) : void {
        echo "User Profile";
    }

}
