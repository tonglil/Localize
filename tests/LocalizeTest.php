<?php namespace Localize\Tests;

use Localize\Localize;

class LocalizeTest extends \PHPUnit_Framework_TestCase
{
    private $localize;

    public function setup()
    {
        $this->localize = new Localize('CA');
    }

    public function testNewWithCountry()
    {
        $localize = new Localize('Canada');
        $this->assertEquals('CA', $localize->getLocale());
    }

    public function testSetLocale()
    {
        $this->assertTrue($this->localize->setLocale('CA'));
    }

    public function testSetLocaleParseException()
    {
        $this->setExpectedException('Localize\Exception\LocaleParseException');
        $this->localize->setLocale('BAD');
    }

    public function testSetLocaleSupportException()
    {
        $this->setExpectedException('Localize\Exception\LocaleSupportException');
        $this->localize->setLocale(null);
    }

    public function testGetLocale()
    {
        $this->assertEquals('CA', $this->localize->getLocale());
    }

    public function testRegex()
    {
        $result = $this->localize->regex('555-555-5555', '/^(\d{3})-(\d{3})-(\d{4})$/' , '$1 $2 $3');
        $this->assertEquals('555 555 5555', $result);
    }

    public function testRegexWithOptionals()
    {
        $result = $this->localize->regex('123456789 AB 0123', '/^(\d{9})\D?([A-Z]{2})\D?(\d{4})$/' , '$1$2$3');
        $this->assertEquals('123456789AB0123', $result);
    }

    public function testRegexManyToOne()
    {
        $regexes = array(
            '/^(\d{3}) (\d{3}) (\d{4})$/',
            '/^(\d{3})-(\d{3})-(\d{4})$/',
        );

        $replaces = '($1) $2-$3';

        $result = $this->localize->regexManyToOne('555 555 5555', $regexes, $replaces);
        $this->assertEquals('(555) 555-5555', $result);

        $result = $this->localize->regexManyToOne('555-555-5555', $regexes, $replaces);
        $this->assertEquals('(555) 555-5555', $result);

        $result = $this->localize->regexManyToOne('should get null', $regexes, $replaces);
        $this->assertEquals(null, $result);
    }

    public function testRegexMany()
    {
        $regexes = array(
            '/^(\d{3}) (\d{3}) (\d{4})$/',
            '/^(\w{3}) (\w{3}) (\w{4})$/',
        );
        $replaces = array(
            '($1) $2-$3',
            '$1$2$3',
        );

        $result = $this->localize->regexMany('555 555 5555', $regexes, $replaces);
        $this->assertEquals('(555) 555-5555', $result);

        $result = $this->localize->regexMany('abc def ghij', $regexes, $replaces);
        $this->assertEquals('abcdefghij', $result);

        $result = $this->localize->regexMany('abc def ghij oops', $regexes, $replaces);
        $this->assertEquals(null, $result);
    }

    public function testUnmatchedRegexException()
    {
        $regexes = array(
            '/^(\d{3}) (\d{3}) (\d{4})$/',
            '/^(\w{3}) (\w{3}) (\w{4})$/',
        );
        $replaces = array(
            '($1) $2-$3',
        );

        $this->setExpectedException('Localize\Exception\UnmatchedRegexException');
        $this->localize->regexMany('555 555 5555', $regexes, $replaces);
    }
}
