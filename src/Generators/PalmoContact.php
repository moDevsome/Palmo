<?php

namespace moDevsome\Palmo\Generators;

use moDevsome\Palmo\Enums\PhoneFormat;
use moDevsome\Palmo\Objects\Person;

class PalmoContact
{

    private PalmoString $stringGenerator;
    private PalmoPerson $personGenerator;
    private PalmoBool $boolGenerator;

    /**
     * Specific method to generate UK phone number
     * Prefix list are pulled from https://en.wikipedia.org/wiki/Telephone_numbers_in_the_United_Kingdom
     * @return string
     */
    private function generateUKPhone(): string
    {
        $prefix = [
            '020',
            '029',
            '0113',
            '0116',
            '0121',
            '0151',
            '01382',
            '01386',
            '01865',
            '01204',
            '015396',
            '016977'
        ];

        $usedPrefix = $prefix[rand(0, count($prefix) - 1)];

        return match ($usedPrefix) {
            '020', '029' => $usedPrefix . ' ' . $this->stringGenerator->num(4, 4) . ' ' . $this->stringGenerator->num(4, 4),
            '0113', '0116', '0121', '0151' => $usedPrefix . ' ' . $this->stringGenerator->num(3, 3) . ' ' . $this->stringGenerator->num(4, 4),
            '01382', '01386', '01865' => $usedPrefix . ' ' . $this->stringGenerator->num(6, 6),
            '01204', '015396' => $usedPrefix . ' ' . $this->stringGenerator->num(5, 5),
            '016977' => $usedPrefix . ' ' . $this->stringGenerator->num(5, 4),
        };
    }

    /**
     * Specific method to generate Ireland phone number
     * Informations are pulled from https://en.wikipedia.org/wiki/Telephone_numbers_in_the_Republic_of_Ireland
     * @param bool $isCellPhone
     * @return string
     */
    private function generateIrelandPhone(bool $isCellPhone): string
    {
        $areas = [
            'dublin',
            '5-digit',
            '6-digit',
            '7-digit',
            'mobile'
        ];

        $usedFormat = $isCellPhone ? $areas['mobile'] : $areas[rand(0, count($areas) - 1)];
        return match ($usedFormat) {
            'dublin' => '01 ' . $this->stringGenerator->num(3, 3) . ' ' . $this->stringGenerator->num(4, 4),
            '5-digit' => '0' . $this->stringGenerator->num(2, 2) . ' ' . $this->stringGenerator->num(5, 5),
            '6-digit' => '0' . $this->stringGenerator->num(2, 2) . ' ' . $this->stringGenerator->num(3, 3) . ' ' . $this->stringGenerator->num(3, 3),
            '7-digit' => '0' . $this->stringGenerator->num(2, 2) . ' ' . $this->stringGenerator->num(3, 3) . ' ' . $this->stringGenerator->num(4, 4),
            'mobile' => '08' . $this->stringGenerator->num(1, 1) . ' ' . $this->stringGenerator->num(3, 3) . ' ' . $this->stringGenerator->num(4, 4),
        };
    }

    /**
     * Generate random email address
     * @param int $usernameLength - Optionnal username length
     * @param int $domainLength - Optionnal domaine length
     * @param Person|null $person - Optionnal perso object used to generate the username
     * @return string - Email address
     */
    public function email(int $usernameLength = 22, int $domainLength = 22, Person|null $person = null): string
    {

        // Define username
        $usernameBase = $person ? $person->firstName . '.' . $person->lastName : $this->personGenerator->firstName() . '-' . $this->personGenerator->lastName();
        $usernameBaseLength = strlen($usernameBase);
        $username = strtolower($usernameLength > $usernameBaseLength
            ? $usernameBase . '-' . $this->stringGenerator->alphanum($usernameLength - $usernameBaseLength)
            : substr($usernameBase, 0, $usernameLength));

        // TODO:create domain name with a dedicated generator
        return $username . '@' . strtolower($this->stringGenerator->alpha($domainLength - 3, 6) . '.' . $this->stringGenerator->alpha(3, 3));
    }

    /**
     * Generate random phone number
     * @param PhoneFormat|null $format - Optionnal country phone format
     * @param boolean $international - If TRUE, the phone number will be prefixed with the international identifier
     * @return string - Phone number
     */
    public function phone(PhoneFormat|null $format = null, bool $international = false): string
    {

        $isCellPhone = $this->boolGenerator->gen();
        $format = $format ?? PhoneFormat::USA;

        $number = match ($format) {
            // 0z ccc cc cc
            PhoneFormat::BELGIUM => implode(' ', array_map(
                fn($segment) => str_ireplace('..', $this->stringGenerator->num(2, 2), $segment),
                array($isCellPhone ? '045' : '0' . rand(2, 4), $this->stringGenerator->num(3, 3), '..', '..')
            )),
            PhoneFormat::ENGLAND => $this->generateUKPhone(),
            PhoneFormat::IRELANDE => $this->generateIrelandPhone($isCellPhone),
            PhoneFormat::FRANCE => implode(' ', array_map(
                fn($segment) => str_ireplace('..', $this->stringGenerator->num(2, 2), $segment),
                array('0' . rand(1, 7), '..', '..', '..', '..')
            )),
            PhoneFormat::USA => $this->stringGenerator->num(3, 3) . ' ' . $this->stringGenerator->num(3, 3) . '-' . $this->stringGenerator->num(4, 4),
        };

        // Handle specific international output for USA format
        if ($format === PhoneFormat::USA and $isCellPhone === true)
            return '+1 ' . str_ireplace('-', ' ', $number);

        // Replace the first character of the number ("0") if the output has international format
        return $international === true
            ? substr_replace($number, match ($format) {
                PhoneFormat::BELGIUM => '+32',
                PhoneFormat::ENGLAND => '+44',
                PhoneFormat::IRELANDE => '+353',
                PhoneFormat::FRANCE => '+33',
            } . ' ', 0, 1)
            : $number;
    }

    public function __construct(PalmoString &$stringGenerator, PalmoPerson &$personGenerator, PalmoBool &$boolGenerator)
    {

        $this->stringGenerator = $stringGenerator;
        $this->personGenerator = $personGenerator;
        $this->boolGenerator = $boolGenerator;
    }
}
