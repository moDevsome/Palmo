<?php

class DatabaseUtil {

    private string $dbFolderPath = '';
    private array $loadedDb = []; // A <key, value> array wich contain the loaded databases

    /**
     * Get databases
     * @param array A array of string with 1 entry for each database to get
     * @return array The databases
     */
    public function get(array $dbNames): array {

        $output = array();

        foreach($dbNames as $dbName) {

            if(array_key_exists($dbName, $this->loadedDb)) {

                $output[$dbName] = $this->loadedDb[$dbName];

            }
            else {

                if(!is_string($dbName))
                throw new Exception('Palmo DatabaseUtil error, Database name must be a string.');

                if(strlen($dbName) <= 0)
                    throw new Exception('Palmo DatabaseUtil error, Database name could not be empty.');

                $filePath = $this->dbFolderPath.DIRECTORY_SEPARATOR.'.'.$dbName;

                if(!is_file($filePath))
                    throw new Exception('Palmo DatabaseUtil error, Database file for "'.$dbName.'" does not exist.');

                try {

                    $dbContent = file_get_contents($filePath);
                    if($dbContent === FALSE) {

                        throw new Exception('file_get_contents return FALSE');

                    }
                    else {

                        $this->loadedDb[$dbName] = explode(',', $dbContent);
                        $output[$dbName] = $this->loadedDb[$dbName];

                    }

                }
                catch(Exception $e) {

                    throw new Exception('Palmo DatabaseUtil error, Database file for "'.$dbName.'" could not be loaded. '.$e->getMessage());

                }

            }

        }

        return $output;

    }

    public function __construct() {

        $this->dbFolderPath = dirname(__FILE__, 2).DIRECTORY_SEPARATOR.'db';

    }
}
?>