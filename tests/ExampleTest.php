<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase {

    public function example() : string {
        // Gebe eine Zahlenfolge von 1-30 getrennt per Leerzeichen aus
        // Alle Zahlen die durch 3 Teilbar sind sollen durch ein A ersetzt werden
        // Alle Zahlen die durch 5 Teilbar sind sollen durch ein B ersetzt werden
        // Alle Zahlen die durch 3 und 5 Teilbar sind sollen durch ein C ersetzt werden

        /** @var string $string */
        $string = '';

        for ( $i = 1; $i <= 30; $i++ ) {
            if ( $i % 5 === 0 && $i % 3 === 0 ) {
                $string .= "C ";
            }
            elseif( $i % 5 === 0 ) {
                $string .= "B ";
            }
            elseif( $i % 3 === 0 ) {
                $string .= "A ";
            }
            else {
                $string .= "{$i} ";
            }
        }

        return rtrim( $string );
    }

    public function testExample() : void {
        /** @var string $value */
        $value = "1 2 A 4 B A 7 8 A B 11 A 13 14 C 16 17 A 19 B A 22 23 A B 26 A 28 29 C";

        $this->assertSame( $this->example(), $value );
    }

}
