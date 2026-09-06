<?php

namespace moDevsome\Palmo\Generators;

use moDevsome\Palmo\Enums\DateTimeMoment;
use moDevsome\Palmo\Utils\DatabaseUtil;
use moDevsome\Palmo\Enums\PersonGender;
use moDevsome\Palmo\Objects\Person;

class PalmoPerson
{

    private PalmoDate $dateGenerator;

    private array $firstNamesDb = ['FEMALE' => [], 'MALE' => []];
    private array $lastNamesDb = [];

    /**
     * Generate a random person firstname
     * @param int $maxLength Output max length
     * @param PersonGender|null $gender - Optionnal gender
     * @return string A random last name
     */
    public function firstName(int $maxLength = 20, PersonGender|null $gender = null): string
    {

        $usedGender = $gender ?? [PersonGender::FEMALE, PersonGender::MALE][rand(0, 1)];
        $filteredList = array_filter($this->firstNamesDb[$usedGender->value], fn($firstName) => strlen($firstName) <= $maxLength);
        return $filteredList[rand(0, count($filteredList) - 1)];
    }

    /**
     * Generate a random person lastname
     * @param int $maxLength - Output max length
     * @return string - A random last name
     */
    public function lastName(int $maxLength = 20): string
    {

        $filteredList = array_filter($this->lastNamesDb, fn($lastName) => strlen($lastName) <= $maxLength);
        return $filteredList[rand(0, count($filteredList) - 1)];
    }

    /**
     * Generate a random person birthdate
     * @return string - A ramdom birthdate with the following format: Y-m-d
     */
    public function birthDate(): string
    {

        return $this->dateGenerator->gen(DateTimeMoment::PAST, 20, 80)->format('Y-m-d');
    }

    /**
     * Generate a random person object
     * @param int $lastNameMaxLength - Max length of the last name string
     * @param int $firstNameMaxLength - Max length of the first name string
     * @param PersonGender|null $gender - Optionnal gender
     * @return Person - A random person object
     */
    public function gen(int $lastNameMaxLength = 20, int $firstNameMaxLength = 20, PersonGender|null $gender = null): Person
    {

        return new Person($gender, $this->lastName($lastNameMaxLength), $this->firstName($firstNameMaxLength, $gender), $this->birthDate());
    }

    public function __construct(PalmoDate &$dateGenerator, DatabaseUtil &$databaseUtil)
    {

        $this->dateGenerator = &$dateGenerator;

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
