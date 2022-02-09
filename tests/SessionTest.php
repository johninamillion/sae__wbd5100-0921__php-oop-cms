<?php

namespace Tests;

use CMS\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase {

    public function testAddValue() : void {
        // Funktionsaufruf über die Klasse Session
        Session::addValue( 'test1', 'foo' );

        // Überprüfen ob der Wert in der Superglobalen $_SESSION gespeichert wurde
        $this->assertSame( $_SESSION[ 'test1' ], 'foo' );
    }

    public function testHasValueReturnTrue() : void {
        // Funktionsaufruf über die Klasse Session um ein Wert hinzuzufügen
        Session::addValue( 'test2', 'foo' );

        $this->assertTrue( Session::hasValue( 'test2' ) );
    }

    public function testHasValueReturnFalse() : void {
        $this->assertFalse( Session::hasValue( 'test3' ) );
    }

}
