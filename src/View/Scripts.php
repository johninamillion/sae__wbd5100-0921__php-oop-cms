<?php

namespace CMS\View;

final class Scripts {

    private array $scripts = [];

    /**
     * Add script
     * @access  public
     * @param   string  $id
     * @param   string  $src
     * @return  void
     */
    public function addScript( string $id, string $src ) : void {
        $this->scripts[ $id ] = $src;
    }

    /**
     * Print scripts
     * @access  public
     * @return  void
     */
    public function printScripts() : void {
        /** @var string $file_ext */
        $file_ext = DEBUG === TRUE ? '.js' : '.min.js';

        foreach ( $this->scripts as $id => $src ) {
            echo "<script id=\"{$id}-js\" type=\"text/javascript\" src=\"{$src}{$file_ext}\"></script>";
        }
    }

}