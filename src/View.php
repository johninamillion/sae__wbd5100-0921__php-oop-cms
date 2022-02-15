<?php

namespace CMS;

use CMS\View\Data;
use CMS\View\Document;
use CMS\View\Scripts;
use CMS\View\Stylesheets;

class View {

    public ?Data $Data = NULL;

    public ?Document $Document = NULL;

    public ?Scripts  $Scripts = NULL;

    public ?Stylesheets $Stylesheets = NULL;

    /**
     * @access  public
     * @constructor
     */
    public function __construct() {
        $this->Data = new Data();
        $this->Document = new Document();
        $this->Scripts = new Scripts();
        $this->Stylesheets = new Stylesheets();

        $this->addDefaultStylesheets();
    }

    /**
     * Add default Stylesheets
     * @access  private
     * @return  void
     */
    private function addDefaultStylesheets() : void {
        // Font Awesome
        $this->Stylesheets->addStylesheet( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/solid' );
    }

    /**
     * Get template part
     * @access  public
     * @param   string  $template_part
     * @return  void
     */
    public function getTemplatePart( string $template_part ) : void {
        /** @var string $template_file */
        $template_file = APPLICATION_TEMPLATE_DIR . DIRECTORY_SEPARATOR . "$template_part.php";

        // Überprüfen ob das Template existiert und eine Fehlermeldung ausgeben, falls nicht
        if ( file_exists( $template_file ) === FALSE ) {
            trigger_error(
                sprintf(
                    'Template part not found (%1$s).',
                    $template_file
                ),
                E_USER_NOTICE
            );
        }
        // Template einbinden, wenn dieses existiert
        else {

            include $template_file;
        }
    }

}
