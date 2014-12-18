<?php namespace Localize\Tests\Locales;

use Localize\Localize;

class CATest extends \PHPUnit_Framework_TestCase
{
    private $localize;

    public function setup()
    {
        $this->localize = new Localize('CA');
    }

    public function testIsCA()
    {
        $this->assertEquals('CA', $this->localize->getLocale());
    }

    public function testCountry()
    {
        $this->assertEquals('CA', $this->localize->country('Canada', true));
        $this->assertEquals('Canada', $this->localize->country('CANADA'));
        $this->assertNull($this->localize->country('null'));
    }

    public function testRegionsShort()
    {
        $this->assertNull($this->localize->region('', true));

        $this->assertEquals('AB', $this->localize->region('alberta', true));
        $this->assertEquals('BC', $this->localize->region('british columbia', true));
        $this->assertEquals('MB', $this->localize->region('manitoba', true));
        $this->assertEquals('NB', $this->localize->region('new brunswick', true));
        $this->assertEquals('NL', $this->localize->region('newfoundland and labrador', true));
        $this->assertEquals('NT', $this->localize->region('northwest territories', true));
        $this->assertEquals('NS', $this->localize->region('nova scotia', true));
        $this->assertEquals('NU', $this->localize->region('nunavut', true));
        $this->assertEquals('ON', $this->localize->region('ontario', true));
        $this->assertEquals('PE', $this->localize->region('prince edward island', true));
        $this->assertEquals('QC', $this->localize->region('quebec', true));
        $this->assertEquals('SK', $this->localize->region('saskatchewan', true));
        $this->assertEquals('YT', $this->localize->region('yukon', true));

        $this->assertEquals('AB', $this->localize->region('ab', true));
        $this->assertEquals('BC', $this->localize->region('bc', true));
        $this->assertEquals('MB', $this->localize->region('mb', true));
        $this->assertEquals('NB', $this->localize->region('nb', true));
        $this->assertEquals('NL', $this->localize->region('nl', true));
        $this->assertEquals('NT', $this->localize->region('nt', true));
        $this->assertEquals('NS', $this->localize->region('ns', true));
        $this->assertEquals('NU', $this->localize->region('nu', true));
        $this->assertEquals('ON', $this->localize->region('on', true));
        $this->assertEquals('PE', $this->localize->region('pe', true));
        $this->assertEquals('QC', $this->localize->region('qc', true));
        $this->assertEquals('SK', $this->localize->region('sk', true));
        $this->assertEquals('YT', $this->localize->region('yt', true));
    }

    public function testRegionsLong()
    {
        $this->assertNull($this->localize->region('', true));

        $this->assertEquals('Alberta', $this->localize->region('alberta'));
        $this->assertEquals('British Columbia', $this->localize->region('british columbia'));
        $this->assertEquals('Manitoba', $this->localize->region('manitoba'));
        $this->assertEquals('New Brunswick', $this->localize->region('new brunswick'));
        $this->assertEquals('Newfoundland and Labrador', $this->localize->region('newfoundland and labrador'));
        $this->assertEquals('Northwest Territories', $this->localize->region('northwest territories'));
        $this->assertEquals('Nova Scotia', $this->localize->region('nova scotia'));
        $this->assertEquals('Nunavut', $this->localize->region('nunavut'));
        $this->assertEquals('Ontario', $this->localize->region('ontario'));
        $this->assertEquals('Prince Edward Island', $this->localize->region('prince edward island'));
        $this->assertEquals('Quebec', $this->localize->region('quebec'));
        $this->assertEquals('Saskatchewan', $this->localize->region('saskatchewan'));
        $this->assertEquals('Yukon', $this->localize->region('yukon'));

        $this->assertEquals('Alberta', $this->localize->region('ab'));
        $this->assertEquals('British Columbia', $this->localize->region('bc'));
        $this->assertEquals('Manitoba', $this->localize->region('mb'));
        $this->assertEquals('New Brunswick', $this->localize->region('nb'));
        $this->assertEquals('Newfoundland and Labrador', $this->localize->region('nl'));
        $this->assertEquals('Northwest Territories', $this->localize->region('nt'));
        $this->assertEquals('Nova Scotia', $this->localize->region('ns'));
        $this->assertEquals('Nunavut', $this->localize->region('nu'));
        $this->assertEquals('Ontario', $this->localize->region('on'));
        $this->assertEquals('Prince Edward Island', $this->localize->region('pe'));
        $this->assertEquals('Quebec', $this->localize->region('qc'));
        $this->assertEquals('Saskatchewan', $this->localize->region('sk'));
        $this->assertEquals('Yukon', $this->localize->region('yt'));
    }

    public function testPostalCode()
    {
        $match = 'V6B 3H7';
        $this->assertEquals($match, $this->localize->postalCode('v6b3h7'));
        $this->assertEquals($match, $this->localize->postalCode('v6b 3h7'));
    }

    public function testPostalCodeNull()
    {
        $this->assertNull($this->localize->postalCode('not a postal code'));
        $this->assertNull($this->localize->postalCode('v66 3a7'));
    }

    public function testPhone()
    {
        $match = '123-456-7890';
        $this->assertEquals($match, $this->localize->phone('1234567890'));
        $this->assertEquals($match, $this->localize->phone('123 456 7890'));
        $this->assertEquals($match, $this->localize->phone('123.456-7890'));
        $this->assertEquals($match, $this->localize->phone('(123) 456-7890'));
        $this->assertEquals($match, $this->localize->phone('+1 (123) 456-7890'));
        $this->assertEquals($match, $this->localize->phone('1 1234567890'));
    }

    public function testPhoneNull()
    {
        $this->assertNull($this->localize->phone('not a phone number'));
        $this->assertNull($this->localize->phone('abc-def-ghij'));
    }

    public function testPhoneE164Full()
    {
        $match = '011-1-123-456-7890';
        $this->assertEquals($match, $this->localize->phoneE164('1234567890'));
        $this->assertEquals($match, $this->localize->phoneE164('123 456 7890'));
        $this->assertEquals($match, $this->localize->phoneE164('123.456-7890'));
        $this->assertEquals($match, $this->localize->phoneE164('(123) 456-7890'));
        $this->assertEquals($match, $this->localize->phoneE164('+1 (123) 456-7890'));
        $this->assertEquals($match, $this->localize->phoneE164('1 1234567890'));
    }

    public function testPhoneE164CountryOnly()
    {
        $match = '+1-123-456-7890';
        $this->assertEquals($match, $this->localize->phoneE164('1234567890', false));
        $this->assertEquals($match, $this->localize->phoneE164('123 456 7890', false));
        $this->assertEquals($match, $this->localize->phoneE164('123.456-7890', false));
        $this->assertEquals($match, $this->localize->phoneE164('(123) 456-7890', false));
        $this->assertEquals($match, $this->localize->phoneE164('+1 (123) 456-7890', false));
        $this->assertEquals($match, $this->localize->phoneE164('1 1234567890', false));
    }

    public function testPhoneE164ull()
    {
        $this->assertNull($this->localize->phoneE164('not a phone number'));
        $this->assertNull($this->localize->phoneE164('abc-def-ghij'));
    }
}
