<?php

namespace CMS\View;

final class Document {

    const DEFAULT_CHARSET = 'UTF-8';

    const DEFAULT_LANGUAGE = 'en';

    const DEFAULT_TITLE = 'My Application';

    const DEFAULT_VIEWPORT = 'width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0';

    const TEXT_DIRECTION_LTR = 'ltr';
    const TEXT_DIRECTION_RTL = 'rtl';

    private string $charset = self::DEFAULT_CHARSET;

    private string $language = self::DEFAULT_LANGUAGE;

    private string $textDirection = self::TEXT_DIRECTION_LTR;

    private string $title = self::DEFAULT_TITLE;

    private string $viewport = self::DEFAULT_VIEWPORT;

    /**
     * Print charset in meta tag
     * @access  public
     * @return  void
     */
    public function charset() : void {
        echo "<meta charset=\"{$this->charset}\">";
    }

    /**
     * Print lang attribute
     * @access  public
     * @return  void
     */
    public function language() : void {
        echo "lang=\"{$this->language}\"";
    }

    /**
     * Print title tag
     * @access  public
     * @return  void
     */
    public function title() : void {
        echo "<title>{$this->title}</title>";
    }

    /**
     * Print viewport meta tag
     * @access  public
     * @return  void
     */
    public function viewport() : void {
        echo "<meta name=\"viewport\" content=\"{$this->viewport}\">";
    }

    /**
     * Print dir attribute
     * @access  public
     * @return  void
     */
    public function textDirection() : void {

        echo "dir=\"{$this->textDirection}\"";
    }

    /**
     * Set charset for meta tag
     * @access  public
     * @param   string  $charset
     * @return  void
     */
    public function setCharset( string $charset ) : void {
        $this->charset = $charset;
    }

    /**
     * Set language for lang attribute on html tag
     * @access  public
     * @param   string  $language
     * @return  void
     */
    public function setLanguage( string $language ) : void {
        $this->language = $language;
    }

    /**
     * Set text direction for dir attribute on html tag
     * @access  public
     * @param   string  $text_direction
     * @return  void
     */
    public function setTextDirection( string $text_direction ) : void {
        /** @var array $valide_text_directions */
        $valid_text_directions = [ 'ltr', 'rtl' ];

        if ( in_array( $text_direction, $valide_text_directions ) === FALSE ) {
            trigger_error(
                sprintf(
                    '\'%1$s\' is no valid text direction.',
                    $text_direction
                ),
                E_USER_NOTICE
            );
        }
        else {
            $this->textDirection = $text_direction;
        }
    }

    /**
     * Set document title for title tag
     * @access  public
     * @param   string  $title
     * @return  void
     */
    public function setTitle( string $title ) : void {
        $this->title = $title;
    }

    /**
     * Set document viewport for meta tag
     * @access  public
     * @param   string  $viewport
     * @return  void
     */
    public function setViewport( string $viewport ) : void {
        $this->viewport = $viewport;
    }

}