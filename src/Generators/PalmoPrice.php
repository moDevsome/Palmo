<?php

namespace moDevsome\Palmo\Generators;

use InvalidArgumentException;
use moDevsome\Palmo\Objects\Price;

class PalmoPrice
{
    /**
     * Generate a random float as price amount, the value is round to 2 digits
     * @param int $min - Minimum value
     * @param int $max - Maximum value
     * @throws InvalidArgumentException
     * @return float
     */
    public function amount(float $min = 0.10, float $max = 9999.90): float
    {

        if ($min >= $max)
            throw new InvalidArgumentException('Amount Min value could not be HIGHER THAN the amount Max value');

        return round(rand($min * 100, $max * 100) / 100, 2);
    }

    /**
     * Generate a random price object
     * @param int $minAmount - Minimum amount value (tax included)
     * @param int $maxAmount - Maximum amount value (tax included)
     * @param float|null $taxRate - A tax rate between 0 and 100
     * @throws InvalidArgumentException
     * @return Price
     */
    public function gen(float $minAmount = 0.10, float $maxAmount = 9999.90, float|null $taxRate = null): Price
    {

        $taxRate = $taxRate === null ? rand(0, 100) : $taxRate;

        if ($taxRate < 0 or $taxRate > 100)
            throw new InvalidArgumentException('The given tax rate could not be LOWER THAN 0 or HIGHER THAN 100');

        $taxIncludedAmount = $this->amount($minAmount, $maxAmount);
        $taxExludedAmount = round($taxIncludedAmount / (1 + ($taxRate / 100)), 2);

        return new Price($taxIncludedAmount, $taxExludedAmount, round($taxIncludedAmount - $taxExludedAmount, 2), $taxRate);
    }
}
