<?php namespace Localize;

use Localize\Exception\LocaleParseException;
use Localize\Exception\LocaleSupportException;
use Localize\Exception\UnmatchedRegexException;

class Localize
{
    private $locale;

    private $mapping = array();

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

    public function __construct($locale = 'CA')
    {
        $locale = $this->country($locale, true);
        $this->setLocale($locale);
    }

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

    public function getLocale()
    {
        return $this->locale;
    }

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

    private function formatter($type, $input)
    {
        return $this->regex($input, $this->mapping[$type]['regex'], $this->mapping[$type]['format']);
    }

    public function regex($input, $regex, $replace)
    {
        if (preg_match($regex, $input)) {
            return preg_replace($regex, $replace, $input);
        } else {
            return null;
        }
    }

    public function regexManyToOne($input, array $regex, $replace)
    {
        foreach ($regex as $rule) {
            if ($match = $this->regex($input, $rule, $replace)) {
                return $match;
            }
        }

        return null;
    }

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

    public function country($country, $short = false)
    {
        $this->mapping = array_merge($this->mapping, array(
            'countries' => $this->countries,
        ));

        return $this->comparator('countries', $country, $short);
    }

    public function region($region, $short = false)
    {
        return $this->comparator('regions', $region, $short);
    }

    public function postalCode($postalCode)
    {
        return $this->formatter('postalCode', strtoupper($postalCode));
    }

    public function phone($phone)
    {
        return $this->formatter('phoneNumber', $phone);
    }
}
