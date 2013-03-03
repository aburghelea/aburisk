<?php

require_once("../base/IScaffold.inc.php");
require_once("../config/Database.php");
require_once("../config/MySqliIHelper.php");
class Scaffold extends MySqliIHelper implements IScaffold
{
    private $table;

    const GET_ROWS_SQL = "SELECT * FROM %s WHERE %s %s %s";



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

//        return $this->get_rows_from_db($orderby, $direction, $limit, $where_clause, $format, $value);
        return $this->get_rows_from_db($value, $format, $where_clause, $orderby, $direction, $limit );

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

//        return $this->get_rows_from_db($orderby, $direction, $limit, $where_clause, $format, $value);
        return $this->get_rows_from_db($value, $format, $where_clause, $orderby, $direction, $limit );
    }

    public function getCustomRows($query)
    {
        $begining = substr(trim($query),0, strlen(Scaffold::C_SELECT));
        if (strcasecmp($begining, Scaffold::C_SELECT) == 0){
            echo "Will GET BY QUERY<br/>";
            return $this->run($query, null, null, 'false');
        }

        return null;
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

//    protected  function get_rows_from_db($orderby, $direction, $limit, $where_clause, $format, $value)
    protected  function get_rows_from_db($value, $format, $where_clause, $orderby, $direction, $limit )
    { /* Determining whitch optional params's are usable */
        list($orderby, $limit) = $this->build_aditional_params($orderby, $direction, $limit);

        /* formating query based on column, sorting and limits */
        $query = sprintf(Scaffold::GET_ROWS_SQL, $this->table, $where_clause, $orderby, $limit);
        echo "<br/> SOME QUERY ".$query."</br>";

        return $this->run($query, $format, $value);
    }

    private function run($query, $format, $value, $prepare = 'true' )
    {

        echo "Q ".$query." ".$prepare."<br/>";
        $stmt = $this->execute_prepared($query, $format, $value,$prepare);

        /* Binding results to column_headers */
        $row = $this->bind_table_header($stmt);

        /* running query */
        return $this->bind_results($stmt, $row);
    }


}


$x = new Scaffold('planets');
echo "</br>By Array </br>";
$arr = array("containing_galaxy_id" => 1, "id" => 1);
print_r($x->getRowsbyArray($arr, "id", "desc"));
echo "</br>By Field </br>";
print_r($x->getRowsbyField('containing_galaxy_id', "3","id","desc"));
echo "</br>Get Custom Rows</br>";
print_r($x->getCustomRows("SELECT * FROM planets WHERE containing_galaxy_id LIKE 3 ORDER BY id desc"));
echo "</br>";
print_r($x->getCustomRows("INSERT * FROM SHIT"));
echo "</br>";
print_r($x->getCustomRows("   DESC * FROM SHIT"));
echo "</br>";
print_r($x->getCustomRows("SELECT * FROM SHIT   "));
echo "</br>";
?>