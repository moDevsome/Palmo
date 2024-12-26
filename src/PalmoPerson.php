<?php

namespace moDevsome\Palmo;

class PalmoPerson {

    private array $lastNamesDb = [];

    /**
     * Generate a random person lastname
     * @param int $maxLength Output max length
     * @return string A random last name
     */
    public function lastName(int $maxLength = 120): string {

        $filteredList = array_filter($this->lastNamesDb, fn($lastName) => $lastName <= $maxLength);
        return $filteredList[rand(0, count($filteredList) - 1)];

    }

    public function __construct(&$databaseUtil) {

        // Load the databases
        $dbs = $databaseUtil->get(array('lastnames'));
        $this->lastNamesDb = $dbs['lastnames'];

    }
}
?>