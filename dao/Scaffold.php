<?php

require_once("../base/interfaces.inc.php");
require_once("../config/Database.php");

class Scaffold implements IScaffold
{
    private $table;
    private $db;

    const GET_ROWS_SQL = "SELECT * FROM %s WHERE %s LIKE ? %s %s";

    public function __construct($table)
    {
        $this->db = Database::connect();
        $this->table = mysql_real_escape_string($table);
    }


    public function getRows($field, $value, $orderby = '', $direction = 'ASC', $limit = '', $show = '')
    {
        /* Creating empty results array */
        $results = array();

        /* Determining whitch optional id's are usable */
        if ($orderby != '') {
            if (strcasecmp($direction, 'ASC') != 0 && strcasecmp($direction, 'DESC') != 0)
                $direction = 'ASC';
            $orderby = 'ORDER BY ' . $orderby . ' ' . $direction;
        }
        if ($limit != '') {
            $limit = 'LIMIT BY ' . $limit;
        }
        /* TODO: De tinut cont de parametrul $SHOW  */


        /* formating query based on column, sorting and limits */
        $query = sprintf(Scaffold::GET_ROWS_SQL, $this->table, $field , $orderby, $limit);
        /* running query */
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s",$value);
        $stmt->execute();
        $stmt->store_result();

        /* Binding results to column_headers */
        $parameters = array();
        $meta = $stmt->result_metadata();
        while ( $field = $meta->fetch_field() ) {
            $parameters[] = &$row[$field->name];
        }
        call_user_func_array(array($stmt, 'bind_result'), $parameters);


        /* creating arrays for each row in the database */
        while ($stmt->fetch()) {
            $line = array();
            foreach ($row as $key => $value) {
                $line[$key] = $value;
            }

            $results[] = $line;
        }

        return $results;

    }

    /*TODO: cea de a doua  metoda getRows*/

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
print_r($x->getRows('containing_galaxy_id', '3'));
?>