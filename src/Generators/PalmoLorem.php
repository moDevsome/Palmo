<?php

namespace moDevsome\Palmo\Generators;

use Exception;
use InvalidArgumentException;
use moDevsome\Palmo\Utils\DatabaseUtil;

class PalmoLorem
{

    private array $db = array();

    /**
     * Return a random Lorem Ipsum paragraph
     * @param int $maxLength
     * @param int $minLength
     * @return string
     */
    public function paragraph(int $maxLength = 500, int $minLength = 16): string
    {

        if ($minLength >= $maxLength)
            throw new InvalidArgumentException('Lorem paragraph min length could not be HIGHER OR EQUAL THAN the max length');

        $paragraphs = array_filter($this->db, fn($p) => strlen($p) >= $minLength and strlen($p) <= $maxLength);

        if (count($paragraphs) === 0)
            throw new Exception('No one lorem paragraph match with the given min length and max length values');

        return $paragraphs[array_rand($paragraphs)];
    }

    /**
     * Return a random Lorem Ipsum sentence
     * @param int $maxLength
     * @param int $minLength
     * @return string
     */
    public function sentence(int $maxLength = 250, int $minLength = 24): string
    {

        if ($minLength >= $maxLength)
            throw new InvalidArgumentException('Lorem sentence min length could not be HIGHER OR EQUAL THAN the max length');

        $sentences = array();
        foreach ($this->db as $paragraph) {
            $paragraphSentences = explode('|', preg_replace('/[.!?]/', '|', $paragraph));
            $sentences = array_merge(
                $sentences,
                array_filter(
                    $paragraphSentences,
                    fn($s) => strlen($s) >= $minLength and strlen($s) <= $maxLength
                )
            );
        }

        $sentences = array_unique(array_map('trim', $sentences));

        if (count($sentences) === 0)
            throw new Exception('No one lorem sentence match with the given min length and max length values');

        return $sentences[array_rand($sentences)] . '.';
    }

    /**
     * Return a random Lorem Ipsum word
     * @param int $maxLength
     * @param int $minLength
     * @return string
     */
    public function word(int $maxLength = 80, int $minLength = 4): string
    {

        if ($minLength >= $maxLength)
            throw new InvalidArgumentException('Lorem word min length could not be HIGHER OR EQUAL THAN the max length');

        $words = array();
        foreach ($this->db as $paragraph) {
            $paragraphWords = array_map(fn($word) => preg_replace('/[^a-zA-Z\s]/', '', trim($word)), explode(' ', $paragraph));
            $words = array_merge(
                $words,
                array_filter(
                    $paragraphWords,
                    fn($w) => strlen($w) >= $minLength and strlen($w) <= $maxLength
                )
            );
        }

        $words = array_unique(array_map('strtolower', $words));

        if (count($words) === 0)
            throw new Exception('No one lorem word match with the given min length and max length values');

        return $words[array_rand($words)];
    }

    public function __construct(
        DatabaseUtil &$databaseUtil
    ) {

        // Load the databases
        $dbs = $databaseUtil->get(array('lorem'));
        $this->db = $dbs['lorem'];
    }
}
