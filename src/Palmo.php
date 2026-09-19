<?php

namespace moDevsome\Palmo;

use moDevsome\Palmo\Utils\DatabaseUtil;
use moDevsome\Palmo\Generators\PalmoAddress;
use moDevsome\Palmo\Generators\PalmoBool;
use moDevsome\Palmo\Generators\PalmoBusiness;
use moDevsome\Palmo\Generators\PalmoContact;
use moDevsome\Palmo\Generators\PalmoDate;
use moDevsome\Palmo\Generators\PalmoNet;
use moDevsome\Palmo\Generators\PalmoNumber;
use moDevsome\Palmo\Generators\PalmoString;
use moDevsome\Palmo\Generators\PalmoPerson;
use moDevsome\Palmo\Generators\PalmoPrice;

class Palmo
{

    readonly PalmoAddress $address;
    readonly PalmoBool $bool;
    readonly PalmoBusiness $business;
    readonly PalmoContact $contact;
    readonly PalmoDate $date;
    readonly PalmoNet $net;
    readonly PalmoNumber $number;
    readonly PalmoPerson $person;
    readonly PalmoPrice $price;
    readonly PalmoString $string;

    public function __construct()
    {

        $databaseUtil = new DatabaseUtil();

        $this->bool = new PalmoBool();
        $this->date = new PalmoDate();
        $this->number = new PalmoNumber();
        $this->string = new PalmoString($this->bool);
        $this->person = new PalmoPerson($this->date, $databaseUtil);
        $this->price = new PalmoPrice();

        $this->address = new PalmoAddress($databaseUtil, $this->string);
        $this->business = new PalmoBusiness($databaseUtil);
        $this->net = new PalmoNet($databaseUtil, $this->number, $this->business);

        $this->contact = new PalmoContact($this->string, $this->person, $this->bool, $this->net);
    }
}
