<?php namespace Localize;

use Localize\Exception\LocaleParseException;
use Localize\Exception\LocaleSupportException;
use Localize\Exception\UnmatchedRegexException;

class Localize
{
    /**
     * The locale.
     * @var string
     */
    private $locale;

    /**
     * The mapping of formats and regular expressions to results.
     * @var array
     */
    private $mapping = array();

    /**
     * The mapping of countries between two-character locales and full names.
     * @var array
     */
    private $countries = array(
        array(
            'short' => 'CA',
            'long' => 'Canada',
        ),
        array(
            'short' => 'US',
            'long' => 'United States',
        ),
    );

    /**
     * Create a new Localize instance.
     * @param string $locale Optional two-character locale
     */
    public function __construct($locale = 'CA')
    {
        $locale = $this->country($locale, true);
        $this->setLocale($locale);
    }

    /**
     * Set the locale of a Localize instance.
     * @param  string   $locale         Optional two-character locale
     * @throws LocaleParseException     When the locale file is malformed
     * @throws LocaleSupportException   When the locale file is not found
     * @return boolean                  True when the locale is set
     */
    public function setLocale($locale = 'CA')
    {
        $file = __DIR__ . '/locales/' . $locale . '.json';

        if (file_exists($file)) {
            $mapping = json_decode(file_get_contents($file), true);

            if (is_array($mapping)) {
                $this->mapping = array_merge($this->mapping, $mapping);
                $this->locale = $locale;

                return true;
            } else {
                throw new LocaleParseException('Locale "' . $locale . '" could not be parsed');
            }
        } else {
            throw new LocaleSupportException('Locale "' . $locale . '" is not supported');
        }
    }

    /**
     * Get the locale of a Localize instance.
     * @return string The two-character locale
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Get the matching short or long value of the input from the mapping.
     * @param  string       $type   The group in the mapping to match
     * @param  string       $input  The given input to look for
     * @param  boolean      $short  True to return the short version
     * @return string|null          The formatted value from the mapping if found, otherwise null
     */
    private function comparator($type, $input, $short = false)
    {
        $input = strtolower(trim($input));

        foreach ($this->mapping[$type] as $value) {
            if (strtolower($value['short']) == $input || strtolower($value['long']) == $input) {
                if ($short) {
                    return $value['short'];
                } else {
                    return $value['long'];
                }
            }
        }

        return null;
    }

    /**
     * Run the input through the regular expression engine based on the type mapping.
     * @param  string       $type   The group in the mapping to match
     * @param  string       $input  The given input
     * @return string|null          The formatted value from the mapping if found, otherwise null
     */
    private function formatter($type, $input)
    {
        return $this->regex($input, $this->mapping[$type]['regex'], $this->mapping[$type]['format']);
    }

    /**
     * Replace the input in the specified format if it matches the regular expression.
     * @param  string       $input      The given input
     * @param  string       $regex      The regular expression to match
     * @param  string       $replace    The format to use when replacing
     * @return string|null              The formatted value based on the replace if a match is found, otherwise null
     */
    public function regex($input, $regex, $replace)
    {
        if (preg_match($regex, $input)) {
            return preg_replace($regex, $replace, $input);
        } else {
            return null;
        }
    }

    /**
     * Replace the input in the specified format if it matches any of multiple regular expressions.
     * @param  string       $input      The given input
     * @param  array        $regex      The regular expressions to match
     * @param  string       $replace    The format to use when replacing
     * @return string|null              The formatted value based on the replace if a match is found, otherwise null
     */
    public function regexManyToOne($input, array $regex, $replace)
    {
        foreach ($regex as $rule) {
            if ($match = $this->regex($input, $rule, $replace)) {
                return $match;
            }
        }

        return null;
    }

    /**
     * Replace the input in the corresponding format if it matches any of multiple regular expressions.
     * @param  string      $input       The given input
     * @param  array       $regex       The regular expressions to match
     * @param  array       $replace     The formats to use when replacing
     * @throws UnmatchedRegexException  When the number of regular expressions do not match the number of replaces
     * @return string|null              The formatted value based on the corresponding replace if a match is found, otherwise null
     */
    public function regexMany($input, array $regex, array $replace)
    {
        if (count($regex) != count($replace)) {
            throw new UnmatchedRegexException('The number of replacements (' . count($replace) . ') do not match the number of regular expressions (' . count($regex) . ')');
        }

        foreach ($regex as $key => $rule) {
            if ($match = $this->regex($input, $rule, $replace[$key])) {
                return $match;
            }
        }

        return null;
    }

    /**
     * Find and format the given country.
     * @param  string       $country    The given input
     * @param  boolean      $short      True to return the short version
     * @return string|null              The formatted country if found, otherwise null
     */
    public function country($country, $short = false)
    {
        $this->mapping = array_merge($this->mapping, array(
            'countries' => $this->countries,
        ));

        return $this->comparator('countries', $country, $short);
    }

    /**
     * Find and format the given region.
     * @param  string       $region The given input
     * @param  boolean      $short  True to return the short version
     * @return string|null          The formatted region if found, otherwise null
     */
    public function region($region, $short = false)
    {
        return $this->comparator('regions', $region, $short);
    }

    /**
     * Find and format the given postal code.
     * @param  string       $postalCode The given input
     * @return string|null              The formatted postal code if found, otherwise null
     */
    public function postalCode($postalCode)
    {
        return $this->formatter('postalCode', strtoupper($postalCode));
    }

    /**
     * Find and format the given phone number into the "common" local format.
     * @param  string       $phone  The given input
     * @return string|null          The formatted phone number if found, otherwise null
     */
    public function phone($phone)
    {
        return $this->formatter('phoneNumber', $phone);
    }

    /**
     * Find and format the given phone number into E.164 formatting.
     * @param  string       $phone  The given input
     * @param  boolean      $full   If the number should include the international calling prefix
     * @return string|null          The formatted phone number if found, otherwise null
     */
    public function phoneE164($phone, $full = true)
    {
        $number = $this->formatter('phoneNumber', $phone);

        if (is_null($number)) {
            return null;
        }

        $country = $this->mapping['phonePrefixes']['country'];
        $international = $this->mapping['phonePrefixes']['international'];

        return $full ? $international . '-' . $country . '-' . $number : '+' . $country . '-' . $number;
    }
}
