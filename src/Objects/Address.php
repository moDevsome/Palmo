<?php

namespace moDevsome\Palmo\Objects;

use moDevsome\Palmo\Enums\Country;

class Address
{
    public function __construct(
        public readonly string $street,
        public readonly string $number,
        public readonly string $city,
        public readonly string $postalCode,
        public readonly string $country
    ) {}
}
