<?php

/**
 * User: Alexandru George Burghelea
 * Date: 10.03.2013
 * Time: 11:37 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . '/Scaffold.php';

/**
 * Generic CRUD/domain
 * It has the same structure as IScaffold, but it delegates it's methods to a Scaffold object
 */
abstract class GenericDao implements IScaffold
{

    protected static $TABLE_NAME;
    private $scaffold;


    function __construct()
    {
        $this->scaffold = new Scaffold(self::$TABLE_NAME);
    }

    public function getRowsByField($field, $value, $orderby = '', $direction = 'ASC', $limit = '', $show = '')
    {

        $rows = $this->scaffold->getRowsByField($field, $value, $orderby, $direction, $limit, $show);

        return empty($rows) ? null : $this->mapRowsToObject($rows);
    }

    public function getRowsByArray($arr, $orderby = '', $direction = 'ASC', $limit = '', $show = '')
    {
        $rows = $this->scaffold->getRowsByArray($arr, $orderby, $direction, $limit, $show);

        return empty($rows) ? null : $this->mapRowsToObject($rows);
    }

    public function getCustomRows($query)
    {
        $rows = $this->scaffold->getCustomRows($query);

        return empty($rows) ? null : $this->mapRowsToObject($rows);
    }

    public function updateRows($arr, $field, $value)
    {
        $this->scaffold->updateRows($arr, $field, $value);
    }

    public function insertRow($arr)
    {
        return $this->scaffold->insertRow($arr);
    }

    public function customQuery($query)
    {
        $this->scaffold->customQuery($query);
    }

    public function deleteRowsByField($field, $value) {
        $this->scaffold->deleteRowsByField($field, $value);
    }

    /**
     * Maps an database row to a Domain object
     * @param $rows array of rows
     * @return array array of domain objects
     */
    private function mapRowsToObject($rows)
    {
        $rtn = array();
        foreach ($rows as $object_row) {
            $classname=get_class($this);

            $object=new $classname();
            foreach ($object_row as $prop => $value) {
                $object->{$prop} = $value;
            }

            $rtn[] = $object;
        }
        return $rtn;
    }
}
