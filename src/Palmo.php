<?php

namespace moDevsome\Palmo;

$dirName = dirname(__FILE__);
require_once $dirName.'/PalmoBool.php';
require_once $dirName.'/PalmoNumber.php';
require_once $dirName.'/PalmoString.php';

class Palmo {

    readonly PalmoBool $bool;
    readonly PalmoNumber $number;
    readonly PalmoString $string;

    public function __construct() {
        $this->bool = new PalmoBool();
        $this->number = new PalmoNumber();
        $this->string = new PalmoString();
    }
}
?>