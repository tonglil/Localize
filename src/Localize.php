<?php namespace Localize;

use Localize\Exception\LocaleParseException;
use Localize\Exception\LocaleSupportException;
use Localize\Exception\LocaleNotSetException;

class Localize
{
    private $locale;

    private $mapping = [];

    private $countries = [
        [
            'short' => 'CA',
            'long' => 'Canada',
        ], [
            'short' => 'US',
            'long' => 'United States',
        ]
    ];

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
        if ($this->locale) {
            return $this->locale;
        } else {
            throw new LocaleNotSetException('The locale is not set');
        }
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
        return $this->regex($input, $this->mapping[$type]['regex'], $this->mapping[$type]['regex-replace']);
    }

    public function regex($input, $regex, $replace)
    {
        if (preg_match($regex, $input)) {
            return preg_replace($regex, $replace, $input);
        } else {
            return null;
        }
    }

    public function country($country, $short = false)
    {
        $this->mapping = array_merge($this->mapping, [
            'countries' => $this->countries,
        ]);

        return $this->comparator('countries', $country, $short);
    }

    public function region($region, $short = false)
    {
        return $this->comparator('regions', $region, $short);
    }

    public function postal_code($postal_code)
    {
        return $this->formatter('postal_code', strtoupper($postal_code));
    }

    public function phone($phone)
    {
        return $this->formatter('phone_number', $phone);
    }
}
