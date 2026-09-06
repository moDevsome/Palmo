<?php

namespace moDevsome\Palmo\Objects;

use moDevsome\Palmo\Enums\PersonGender;

class Person
{
    public function __construct(public readonly PersonGender|null $gender, public readonly string $lastName, public readonly  string $firstName, public readonly string $birthDate) {}
}
