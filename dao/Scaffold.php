<?php

require_once("../base/IScaffold.inc.php");
require_once("../config/Database.php");
require_once("../config/MySqliIHelper.php");
class Scaffold extends MySqliIHelper implements IScaffold
{
    private $table;

    const GET_ROWS_SQL_BY_FIELD = "SELECT * FROM %s WHERE %s = ? %s %s";
    const GET_ROWS_SQL_BY_ARRAY = "SELECT * FROM %s WHERE %s %s %s";



    public function __construct($table)
    {
        parent::__construct(Database::connect());
        $this->table = mysql_real_escape_string($table);
    }

    /*
     * extrage intrarile din tabela cu conditia $field = $value; le ordoneaza corespunzator daca parametrii de
     * ordonare au fost trimisi; limiteaza rezultatele daca parametrii $limit si $show sunt setati
     * intoarce un array asociativ sau null daca nu a fost gasita nicio intrare
     */
    public function getRowsbyField($field, $value, $orderby = '', $direction = 'ASC', $limit = '', $show = '')
    {
        /* Creating empty results array */
        $format = "s";
        $where_clause = $this->build_where_clause($field);

        return $this->get_rows_from_db($orderby, $direction, $limit, $where_clause, $format, $value);

    }

    /*
     * extrage intrarile din tabela cu conditiile $key => $value, unde $key => $value sunt elemente din $arr;
     * le ordoneaza corespunzator daca parametrii de ordonare au fost trimisi; limiteaza rezultatele daca
     * parametrii $limit si $show sunt setati
     * intoarce un array asociativ sau null daca nu a fost gasita nicio intrare
     */
    public function getRowsByArray($arr, $orderby = '', $direction = 'ASC', $limit = '', $show = '')
    {

        $format = array(str_repeat('s', count($arr)));
        $value = array_values($arr);
        $where_clause = $this->build_where_clause($arr);

        return $this->get_rows_from_db($orderby, $direction, $limit, $where_clause, $format, $value);


    }

    protected  function get_rows_from_db($orderby, $direction, $limit, $where_clause, $format, $value)
    { /* Determining whitch optional params's are usable */
        list($orderby, $limit) = $this->build_aditional_params($orderby, $direction, $limit);

        /* formating query based on column, sorting and limits */
        $query = sprintf(Scaffold::GET_ROWS_SQL_BY_ARRAY, $this->table, $where_clause, $orderby, $limit);

        /* running query */
        $stmt = $this->execute_prepared($query, $format, $value);

        /* Binding results to column_headers */
        $row = $this->bind_table_header($stmt);

        return $this->bind_results($stmt, $row);
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


$x = new Scaffold('planets');
echo "</br>By Array </br>";
$arr = array("containing_galaxy_id" => 1, "id" => 1);
print_r($x->getRowsbyArray($arr, "id", "desc"));
echo "</br>By Field </br>";
print_r($x->getRowsbyField('containing_galaxy_id', "100","id","desc"));
?>