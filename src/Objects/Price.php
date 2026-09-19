<?php

namespace moDevsome\Palmo\Objects;

class Price
{
    public function __construct(public readonly float $includedTaxAmount, public readonly float $excludedTaxAmount, public readonly float $taxAmount, public readonly float $taxRate) {}
}
