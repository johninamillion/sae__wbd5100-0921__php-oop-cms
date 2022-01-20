<?php

namespace CMS\View;

final class Stylesheets {

    private array $stylesheets = [];

    /**
     * Add stylesheet
     * @access  public
     * @param   string  $id
     * @param   string  $href
     * @return  void
     */
    public function addStylesheet( string $id, string $href ) : void {
        $this->stylesheets[ $id ] = $href;
    }

    /**
     * Print Stylesheets
     * @access  public
     * @return  void
     */
    public function printStylesheets() : void {
        /** @var string $file_ext */
        $file_ext = DEBUG === TRUE ? '.css' : '.min.css';

        foreach ( $this->stylesheets as $id => $href ) {
            echo "<link id=\"{$id}-css\" href=\"{$href}{$file_ext}\" rel=\"stylesheet\">";
        }
    }

}