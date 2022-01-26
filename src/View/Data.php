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

    /**
     * Format date from timestamp
     * @access  public
     * @param   int     $timestamp
     * @return  string
     */
    public function formatDate( int $timestamp ) : string {

        return date( DATE_FORMAT, $timestamp );
    }

    /**
     * Format date and time from timestamp
     * @access  public
     * @param   int     $timestamp
     * @return  string
     */
    public function formatDateTime( int $timestamp ) : string {

        return date( DATE_FORMAT . DATE_TIME_SEPARATOR . TIME_FORMAT, $timestamp );
    }

    /**
     * Format time from timestamp
     * @access  public
     * @param   int     $timestamp
     * @return  string
     */
    public function formatTime( int $timestamp ) : string {

        return date( TIME_FORMAT, $timestamp );
    }

}
