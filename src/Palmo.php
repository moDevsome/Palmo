<?php

namespace moDevsome\Palmo;

use moDevsome\Palmo\Utils\DatabaseUtil;
use moDevsome\Palmo\Generators\PalmoAddress;
use moDevsome\Palmo\Generators\PalmoBool;
use moDevsome\Palmo\Generators\PalmoDate;
use moDevsome\Palmo\Generators\PalmoNumber;
use moDevsome\Palmo\Generators\PalmoString;
use moDevsome\Palmo\Generators\PalmoPerson;

class Palmo
{

    readonly PalmoAddress $address;
    readonly PalmoBool $bool;
    readonly PalmoDate $date;
    readonly PalmoNumber $number;
    readonly PalmoString $string;
    readonly PalmoPerson $person;

    public function __construct()
    {

        $databaseUtil = new DatabaseUtil();

        $this->bool = new PalmoBool();
        $this->date = new PalmoDate();
        $this->number = new PalmoNumber();
        $this->string = new PalmoString($this->bool);
        $this->person = new PalmoPerson($this->date, $databaseUtil);

        $this->address = new PalmoAddress($databaseUtil, $this->string);
    }
}
