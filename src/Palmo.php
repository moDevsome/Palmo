<?php

namespace moDevsome\Palmo;

use DatabaseUtil;

$dirName = dirname(__FILE__);

require_once $dirName.'/Utils/DatabaseUtil.php';

require_once $dirName.'/PalmoBool.php';
require_once $dirName.'/PalmoNumber.php';
require_once $dirName.'/PalmoPerson.php';
require_once $dirName.'/PalmoString.php';

class Palmo {

    readonly PalmoBool $bool;
    readonly PalmoNumber $number;
    readonly PalmoString $string;
    readonly PalmoPerson $person;

    public function __construct() {

        $databaseUtil = new DatabaseUtil();

        $this->bool = new PalmoBool();
        $this->number = new PalmoNumber();
        $this->string = new PalmoString();
        $this->person = new PalmoPerson($databaseUtil);
    }
}
?>