<?php

namespace moDevsome\Palmo;
use Exception;

class PalmoNumber {

    /**
     * Generate random int
     * @param int $min Minimum value
     * @param int $max Max value
     * @return int A random int
     */
    public function int(int $min = -9999, int $max = 9999): int {

        if($max <= $min)
            throw new Exception('PalmoNumber::int() exception, max value must be higher than min value');

        return rand($min, $max);

    }

    /**
     * Generate random float
     * @param float $min Minimum value
     * @param float $max Max value
     * @return int A random float
    */
    public function float(float $min = -9999.0, float $max = 9999.0): float {

        if($max <= $min)
            throw new Exception('PalmoNumber::float() exception, max value must be higher than min value');

        $delta = $max - $min;
        $divider = rand(1, 10);
        $append = $delta / $divider;
        $total = $min + $append;

        // Realign output length on the higher input value length
        $minStringLength = strlen((string) $min);
        $maxStringLength = strlen((string) $max);
        $maxLength = $minStringLength > $maxStringLength ? $minStringLength : $maxStringLength;
        $value = (string) $total;
        $output = substr($value, 0, $maxLength);

        return floatval((float) $output);
    }
}
?>