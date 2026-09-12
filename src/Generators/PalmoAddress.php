<?php

namespace moDevsome\Palmo\Generators;

use UnitEnum;
use moDevsome\Palmo\Utils\DatabaseUtil;
use moDevsome\Palmo\Enums\Country;
use moDevsome\Palmo\Enums\StreetType;
use moDevsome\Palmo\Enums\PostalCodeFormat;
use moDevsome\Palmo\Objects\Address;

class PalmoAddress
{
    private PalmoString $stringGenerator;

    private array $citiesDb = [];
    private array $streetsDb = [];

    /**
     * Generate a random street
     * @param StreetType $type - An optionnal street type
     * @return string - A random street name
     */
    public function street(StreetType|null $type = null): string
    {

        return $this->streetsDb[rand(0, count($this->streetsDb) - 1)] . ' ' . strtolower($type === null ? StreetType::STREET->value : $type->value);
    }

    /**
     * Generate a random street number
     * @param bool $withLetter - If true, complete the number with a letter
     * @return string - A random address number
     */
    public function number(bool $withLetter = false): string
    {

        $number = (string) rand(1, 400);
        if ($withLetter)
            $number .= ['A', 'B', 'C', 'D'][rand(0, 3)];

        return $number;
    }

    /**
     * Generate a random city
     * @return string - A random city
     */
    public function city(): string
    {

        return $this->citiesDb[rand(0, count($this->citiesDb) - 1)];
    }

    /**
     * Generate a random country
     * @param Country $country - An optionnal country enum value
     * @return string - A random country
     */
    public function country(Country|null $country = null): string
    {
        if ($country) return $country->value;

        $values = Country::cases();
        $index = rand(0, count($values) - 1);
        return $values[$index]->value;
    }

    /**
     * Generate a random postalCode
     * @param PostalCodeFormat $format - An optionnal country postal code format
     * @return string - A random postalCode
     */
    public function postalCode(PostalCodeFormat|null $format = null): string
    {

        return match ($format) {
            PostalCodeFormat::BELGIUM => (string) rand(1000, 9999),
            PostalCodeFormat::ENGLAND => strtoupper(
                $this->stringGenerator->alpha(2, 1) . '' .
                    $this->stringGenerator->num(2, 1) . ' ' .
                    $this->stringGenerator->num(1) . '' . $this->stringGenerator->alpha(2, 2)
            ),
            PostalCodeFormat::FRANCE_EXTENDED => (string) rand(970000, 976000),
            PostalCodeFormat::IRELANDE => strtoupper(
                $this->stringGenerator->alpha(1, 1) . '' .
                    $this->stringGenerator->num(2, 2) . ' ' .
                    $this->stringGenerator->alphanum(4, 4)
            ),
            PostalCodeFormat::FRANCE, PostalCodeFormat::USA, null => (string) rand(10000, 95000),
        };
    }

    /**
     * Generate a random address
     * @param Country $country- An optionnal country
     * @param PostalCodeFormat $postalCodeFormat- An optionnal country postal code format
     * @param StreetType $streetType - An optionnal street type
     * @param bool $numberWithLetter - If true, complete the number with a letter
     * @return Address - A random address object
     */
    public function gen(
        Country|null $country = null,
        PostalCodeFormat|null $postalCodeFormat = null,
        StreetType|null $streetType = null,
        bool $numberWithLetter = false
    ): Address {

        return new Address(
            $this->street($streetType),
            $this->number($numberWithLetter),
            $this->city(),
            $this->postalCode($postalCodeFormat),
            $this->country($country)
        );
    }

    public function __construct(DatabaseUtil &$databaseUtil, PalmoString &$stringGenerator)
    {

        // Load the databases
        $dbs = $databaseUtil->get(array('cities', 'streets'));
        $this->citiesDb = $dbs['cities'];
        $this->streetsDb = $dbs['streets'];

        $this->stringGenerator = $stringGenerator;
    }
}
