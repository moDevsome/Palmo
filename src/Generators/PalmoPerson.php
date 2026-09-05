<?php

namespace moDevsome\Palmo\Generators;

use moDevsome\Palmo\Utils\DatabaseUtil;
use moDevsome\Palmo\Enums\PersonGender;

class PalmoPerson
{

    private array $firstNamesDb = ['FEMALE' => [], 'MALE' => []];
    private array $lastNamesDb = [];

    /**
     * Generate a random person firstname
     * @param int $maxLength Output max length
     * @param PersonGender|null $gender Optionnal gender
     * @return string A random last name
     */
    public function firstName(int $maxLength = 120, PersonGender|null $gender = null): string
    {

        $usedGender = $gender ?? [PersonGender::FEMALE, PersonGender::MALE][rand(0, 1)];
        $filteredList = array_filter($this->firstNamesDb[$usedGender->value], fn($firstName) => strlen($firstName) <= $maxLength);
        return $filteredList[rand(0, count($filteredList) - 1)];
    }

    /**
     * Generate a random person lastname
     * @param int $maxLength Output max length
     * @return string A random last name
     */
    public function lastName(int $maxLength = 120): string
    {

        $filteredList = array_filter($this->lastNamesDb, fn($lastName) => strlen($lastName) <= $maxLength);
        return $filteredList[rand(0, count($filteredList) - 1)];
    }

    public function __construct(DatabaseUtil &$databaseUtil)
    {

        // Load the databases
        $dbs = $databaseUtil->get(array('lastnames', 'firstnames'));
        $this->lastNamesDb = $dbs['lastnames'];

        $firstNamesDb = ['FEMALE' => [], 'MALE' => []];
        foreach ($dbs['firstnames'] as $firstName) {
            $genderFlag = strpos($firstName, 'F_') === 0 ? 'F_' : 'M_';
            $firstNamesDb[$genderFlag === 'F_' ? 'FEMALE' : 'MALE'][] = str_ireplace($genderFlag, '', $firstName);
        }
        $this->firstNamesDb = $firstNamesDb;
    }
}
