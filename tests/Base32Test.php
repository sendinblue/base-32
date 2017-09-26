<?php

namespace SendinBlue\Tests;

use PHPUnit\Framework\TestCase;
use SendinBlue\Base32;

class Base32Test extends TestCase
{
    public function testDecode()
    {
        // https://tools.ietf.org/html/rfc4648#section-10
        $this->assertEquals('', Base32::decode(''));
        $this->assertEquals('f', Base32::decode('MY======'));
        $this->assertEquals('fo', Base32::decode('MZXQ===='));
        $this->assertEquals('foo', Base32::decode('MZXW6==='));
        $this->assertEquals('foob', Base32::decode('MZXW6YQ='));
        $this->assertEquals('fooba', Base32::decode('MZXW6YTB'));
        $this->assertEquals('foobar', Base32::decode('MZXW6YTBOI======'));

        $this->assertEquals('f', Base32::decode('MY'));
        $this->assertEquals('fo', Base32::decode('MZXQ'));
        $this->assertEquals('foo', Base32::decode('MZXW6'));
        $this->assertEquals('foob', Base32::decode('MZXW6YQ'));
        $this->assertEquals('foobar', Base32::decode('MZXW6YTBOI'));

        $this->expectException(\UnexpectedValueException::class);
        Base32::decode('MZXW6YTBOI=====BOI');

        $this->expectException(\RangeException::class);
        Base32::decode('MZXW6YTBOI======');

        $this->expectException(\OutOfBoundsException::class);
        Base32::decode('WTF');
    }

    public function testEncode()
    {
        // https://tools.ietf.org/html/rfc4648#section-10
        $this->assertEquals('', Base32::encode(''));
        $this->assertEquals('MY======', Base32::encode('f'));
        $this->assertEquals('MZXQ====', Base32::encode('fo'));
        $this->assertEquals('MZXW6===', Base32::encode('foo'));
        $this->assertEquals('MZXW6YQ=', Base32::encode('foob'));
        $this->assertEquals('MZXW6YTB', Base32::encode('fooba'));
        $this->assertEquals('MZXW6YTBOI======', Base32::encode('foobar'));

        $this->assertEquals('', Base32::encode('', false));
        $this->assertEquals('MY', Base32::encode('f', false));
        $this->assertEquals('MZXQ', Base32::encode('fo', false));
        $this->assertEquals('MZXW6', Base32::encode('foo', false));
        $this->assertEquals('MZXW6YQ', Base32::encode('foob', false));
        $this->assertEquals('MZXW6YTB', Base32::encode('fooba', false));
        $this->assertEquals('MZXW6YTBOI', Base32::encode('foobar', false));
    }
}
