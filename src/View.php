<?php

namespace CMS;

class View {

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
        if ( file( $template_file ) !== FALSE ) {
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
