<?php namespace Localize\Tests;

use Localize\Localize;
use Localize\Exception\LocaleParseException;
use Localize\Exception\LocaleSupportException;

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
        try {
            $this->localize->setLocale('BAD');
        } catch (LocaleParseException $e) {
            return;
        }

        $this->fail('An expected exception (LocaleParseException) was not raised.');
    }

    public function testSetLocaleSupportException()
    {
        try {
            $this->localize->setLocale('NONE');
        } catch (LocaleSupportException $e) {
            return;
        }

        $this->fail('An expected exception (LocaleSupportException) was not raised.');
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
    }
}
