<?php

/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 10:49 AM
 * For : PWeb 2013
 */

foreach (glob("../dao/*.php") as $filename)
{
    require_once($filename);
}

$gx = new Galaxie();
$gxs = $gx->getRowsByField('id', '1');
foreach ($gxs as $obj) {
    echo $obj . "\n";
}

$g = new Game();
$gs = $g->getRowsByField('id', '1');
foreach ($gs as $obj) {
    echo $obj . "\n";
}

$p = new Planet();
$ps = $p->getRowsByField('containing_galaxy_id', '1');
foreach ($ps as $obj) {
    echo $obj . "\n<br/>";
}


$pg = new Planet_Game();
$pgs = $pg->getRowsByField('planet_id', '1');
foreach ($pgs as $obj) {
    echo $obj . "\n<br/>";
}

$pn = new Planet_Neighbour();
$pns = $pn->getRowsByField('first_planet_id',1);
foreach ($pns as $obj) {
    echo $obj . "\n<br/>";
}

$ug = new User_Game();
$ugs = $ug->getRowsByField('user_id', '1');
foreach ($ugs as $obj) {
    echo $obj . "\n";
}
?>