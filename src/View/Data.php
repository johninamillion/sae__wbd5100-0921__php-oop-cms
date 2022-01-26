<?php

namespace CMS\View;

final class Data {

    private array $data = [];

    /**
     * Get data for usage in view
     * @access  public
     * @param   string  $key
     * @return  mixed
     */
    public function __get( string $key ) {

        return $this->data[ $key ] ?? NULL;
    }

    /**
     * Add data for usage in view
     * @access  public
     * @param   string  $key
     * @param   mixed   $value
     * @return  void
     */
    public function __set( string $key, $value ) : void {
        $this->data[ $key ] = $value;
    }

}