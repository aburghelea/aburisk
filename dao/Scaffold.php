<?php

include ("../base/interfaces.inc.php");

class Scaffold implements IScaffold
{

    public function __construct($table)
    {
        // TODO: Implement __construct() method.
    }

    public function getRows($field, $value, $orderby = '', $direction = 'ASC', $limit = '', $show = '')
    {
        // TODO: Implement getRows() method.
    }

    public function getCustomRows($query)
    {
        // TODO: Implement getCustomRows() method.
    }

    public function updateRows($arr, $field, $value)
    {
        // TODO: Implement updateRows() method.
    }

    public function insertRow($arr)
    {
        // TODO: Implement insertRow() method.
    }

    public function customQuery($query)
    {
        // TODO: Implement customQuery() method.
    }
}

?>