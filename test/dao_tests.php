<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/10/13
 * Time: 10:48 PM
 * For : PWeb 2013
 */

require_once('../dao/Scaffold.php');

$x = new Scaffold('planets');
echo "</br>By Array </br>";
$arr = array("containing_galaxy_id" => 1, "id" => 1);
print_r($x->getRowsbyArray($arr, "id", "desc"));

echo "</br>By Field " . count($x->getRowsbyField('containing_galaxy_id', "3", "id", "desc", 1, 2)) . " </br>";
print_r($x->getRowsbyField('containing_galaxy_id', "3", "id", "desc", 1, 1));

echo "</br>Get Custom Rows</br></br></br>";
print_r($x->getCustomRows("SELECT * FROM planets WHERE containing_galaxy_id LIKE 3 ORDER BY id desc"));

echo "</br>";
print_r($x->getCustomRows("SELECT * FROM planets"));

echo "</br></br></br>Planets DESCRIBE</br>";
print_r($x->customQuery("DESC planets"));

echo "Insert test <br/>";
$what = array("name" => "Pamant", "containing_galaxy_id" => "1", "image" => "test.jpg");
$x->insertRow($what);

echo "Update test <br/>";
$what2 = array("name" => "Chapa Ai", "containing_galaxy_id" => "2", "image" => "testai.jpg");
$x->updateRows($what2, "id", 26);
?>