<?php
/**
 * Created by JetBrains PhpStorm.
 * User: iceman
 * Date: 3/2/13
 * Time: 3:34 PM
 * To change this template use File | Settings | File Templates.
 */

interface IPoliMethods
{

    public function poli($a);

//    public function poli($a, $b);
}

class PoliMetods implements IPoliMethods
{

    public function poli($a)
    {
        echo "Metoda cu un parametru " . $a . "</br>";

    }

//    public function poli($a, $b)
//    {
//        echo "Metoda cu doi parametri " . $a . " si " . $b . "</br>";
//    }
}

$obj = new PoliMetods();
$obj . poli(1);
$obj . poli(1, 2);
?>