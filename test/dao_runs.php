<?php

/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 10:49 AM
 * For : PWeb 2013
 */

foreach (glob("../dao/actual/*.php") as $filename)
{
    require_once($filename);
}
//
//$gx = new Galaxy();
//$gxs = $gx->getRowsByField('id', '1');
//foreach ($gxs as $obj) {
//    echo $obj . "\n";
//}
//
$g = new Game();
$gs = $g->getRowsByField('1', '1');
foreach ($gs as $obj) {
    echo $obj . "\n";
}
//
//$p = new Planet();
//$ps = $p->getRowsByField('containing_galaxy_id', '1');
//foreach ($ps as $obj) {
//    echo $obj . "\n<br/>";
//}
//
//
//$pg = new Planet_Game();
//$pgs = $pg->getRowsByField('planet_id', '1');
//foreach ($pgs as $obj) {
//    echo $obj . "\n<br/>";
//}
//
//$pn = new Planet_Neighbour();
//$pns = $pn->getRowsByField('first_planet_id',1);
//foreach ($pns as $obj) {
//    echo $obj . "\n<br/>";
//}
//
//$ug = new User_Game();
//$ugs = $ug->getRowsByField('user_id', '1');
//foreach ($ugs as $obj) {
//    echo $obj . "\n";
//}
//
//$u = new User();
//$us = $u->getRowsByField('id','1');
//foreach ($us as $obj) {
//    echo $obj . "\n";
//}
//
//echo "Registering users<br/>";
//echo User::register('iceman', 'gigi@sdasda','1')."</br>\n";
//echo User::register('icesdasman', 'iceman.ftg@gmail.com','1')."</br>\n";
//echo User::register('iceman', 'iceman.ftg@gmail.com','1')."</br>\n";
//echo User::register('aburs', 'abusr.ftg@gmail.com','1')."</br>\n";
//
//echo "User login<br/>\n";
//echo User::login('iceman','1')."</br>\n";
//echo User::login('iceman','4')."</br>\n";
//echo User::login('icemans','1')."</br>\n";
//echo User::login('icemadsadan','1sdasa')."</br>\n";
?>