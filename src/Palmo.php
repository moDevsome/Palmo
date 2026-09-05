<?php

namespace moDevsome\Palmo;

use moDevsome\Palmo\Utils\DatabaseUtil;
use moDevsome\Palmo\Generators\PalmoBool;
use moDevsome\Palmo\Generators\PalmoNumber;
use moDevsome\Palmo\Generators\PalmoString;
use moDevsome\Palmo\Generators\PalmoPerson;

class Palmo
{

    readonly PalmoBool $bool;
    readonly PalmoNumber $number;
    readonly PalmoString $string;
    readonly PalmoPerson $person;

    public function __construct()
    {

        $databaseUtil = new DatabaseUtil();

        $this->bool = new PalmoBool();
        $this->number = new PalmoNumber();
        $this->string = new PalmoString();
        $this->person = new PalmoPerson($databaseUtil);
    }
}
