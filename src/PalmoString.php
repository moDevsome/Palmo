<?php

namespace moDevsome\Palmo;

use moDevsome\Palmo\PalmoBool;
use Exception;

class PalmoString {

    private $alphabet = 'abcdefghijklmnopqrstuvwxyz';
    private $numbers = '0123456789';

    private function _alphanum(int $maxLength = 68, int $minLength = 1, bool $hasDigit, bool $onlyDigit = FALSE): string {

        $boolGen = new PalmoBool;

        if($minLength < 0)
            throw new Exception('PalmoString::alphanum() exception, minLength must be equal or higher than 0');

        if($maxLength <= $minLength)
            throw new Exception('PalmoString::alphanum() exception, maxLength must be higher than minLength');

        if($onlyDigit === TRUE) {

            $chars = str_split($this->numbers);

        }
        else {

            $chars = $hasDigit === TRUE ? str_split($this->alphabet.''.$this->numbers) : str_split($this->alphabet);

        }

        $length = rand($minLength, $maxLength);
        $count = 0;
        $output = array();
        foreach($chars as $char) {

            $count++;
            $char = $chars[rand(0, count($chars) - 1)];
            $output[] = $boolGen->gen() === TRUE ? strtolower($char) : strtoupper($char);

            if(count($output) === $length) break;

        }

        return implode('', $output);

    }

    /**
     * Generate a random alphanumeric string
     * @param int $maxLength Maximum length
     * @param int $minLength Minimum length
     * @return string A random string wich contain only alphanumeric chars in lowercase or uppercase
     */
    public function alphanum(int $maxLength = 68, int $minLength = 1): string {

        return $this->_alphanum($maxLength, $minLength, TRUE);

    }

    /**
     * Generate a random alpha string
     * @param int $maxLength Maximum length
     * @param int $minLength Minimum length
     * @return string A random string wich contain only alpha chars in lowercase or uppercase
     */
    public function alpha(int $maxLength = 68, int $minLength = 1): string {

        return $this->_alphanum($maxLength, $minLength, FALSE);

    }

    /**
     * Generate a random numeric string
     * @param int $maxLength Maximum length
     * @param int $minLength Minimum length
     * @return string A random string wich contain only digital chars
     */
    public function num(int $maxLength = 68, int $minLength = 1): string {

        return $this->_alphanum($maxLength, $minLength, TRUE, TRUE);

    }
}
?>