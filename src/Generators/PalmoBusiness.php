<?php

namespace moDevsome\Palmo\Generators;

use moDevsome\Palmo\Utils\DatabaseUtil;

class PalmoBusiness
{
    private array $companiesDb;
    private array $productsDb;

    /**
     * Generate random company name
     * @param int $maxLength
     * @return string A random company name
     */
    public function company(int $maxLength = 20): string
    {

        $filteredList = array_filter($this->companiesDb, fn($companyName) => strlen($companyName) <= $maxLength);
        return $filteredList[array_rand($filteredList)];
    }

    /**
     * Generate random product
     * @param int $maxLength
     * @return string A random product name
     */
    public function product(int $maxLength = 20): string
    {

        $filteredList = array_filter($this->productsDb, fn($productName) => strlen($productName) <= $maxLength);
        return $filteredList[array_rand($filteredList)];
    }

    public function __construct(DatabaseUtil &$databaseUtil)
    {

        // Load the databases
        $dbs = $databaseUtil->get(array('companies', 'products'));
        $this->companiesDb = $dbs['companies'];
        $this->productsDb = $dbs['products'];
    }
}
