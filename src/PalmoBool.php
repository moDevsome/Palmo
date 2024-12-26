<?php

namespace moDevsome\Palmo;

class PalmoBool {

    /**
     * Generate random boolean value
     * @return bool A random boolean value
     */
    public function gen(): bool {

        return rand(0, 1) === 1;

    }
}
?>