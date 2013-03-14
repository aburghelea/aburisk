<?php

/**
 * User: Alexandru George Burghelea
 * Date: 02.03.2012
 * Time: 11:56 PM
 * For : PWeb 2013
 */

interface IScaffold
{
    /**
     * Extracts the entries from the database matching the condition $field = $value; it orders them if the
     * parameters for ordering have been send; it limits the resulst if $limit and $show are set
     * @param $field
     * @param $value
     * @param string $orderby
     * @param string $direction
     * @param string $limit
     * @param string $show
     * @return array|null an associative array or null if now entry has been found
     */
    public function getRowsByField($field, $value, $orderby = '', $direction = 'ASC', $limit = '', $show = '');

    /**
     * Extracts the entries from the database matching the conditions $key => $value
     * where $key => $value are elements from $arr parameters for ordering
     *  have been send; it limits the resulst if $limit and $show are set
     * @param $arr
     * @param string $orderby
     * @param string $direction
     * @param string $limit
     * @param string $show
     * @return array|null an associative array or null if now entry has been found
     */
    public function getRowsByArray($arr, $orderby = '', $direction = 'ASC', $limit = '', $show = '');

    /**
     * Extracts the entries from the database folowing the SQL query sent as parameter
     * @param $query
     * @return array|null an associative array or null if now entry has been found
     */
    public function getCustomRows($query);

    /**
     * Sets the value of each $key to $value , where $key => $value are elements
     * of $arr, for the entries where $field = $value
     * @param $arr
     * @param $field
     * @param $value
     */
    public function updateRows($arr, $field, $value);

    /**
     * Sets the value of each $key to $value , where $key => $value are elements
     * of $arr
     * @param $arr
     * @return mixed
     */
    public function insertRow($arr);

    /**
     * @param string $query custom query
     * @return array|null query results
     */
    public function customQuery($query);
}

?>