<?php

/**
 * User: Alexandru George Burghelea
 * Date: 02.03.2013
 * Time: 11:56
 * For : PWeb 2013
 */

require_once("../interface/IScaffold.inc.php");
require_once("../database/Database.php");
require_once("../generic/MySqliIHelper.php");

class Scaffold extends MySqliIHelper implements IScaffold
{
    private $table;

    const GET_ROWS_SQL = "SELECT * FROM %s WHERE %s %s %s";
    const INSERT_SQL = "INSERT INTO %s (%s) VALUES (%s)";
    const UPDATE_SQL = "UPDATE %s SET %s WHERE %s";

    public function __construct($table)
    {
        parent::__construct(Database::connect());
        $this->table = mysqli_real_escape_string($this->db, $table);
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

        return $this->get_rows_from_db($value, $format, $where_clause, $orderby, $direction, $limit, $show);

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

        return $this->get_rows_from_db($value, $format, $where_clause, $orderby, $direction, $limit, $show);
    }

    /*
     * extrage intrarile din tabela pe baza query-ului trimis ca parametru
     * intoarce un array asociativ sau null daca nu a fost gasita nicio intrare
     */
    public function getCustomRows($query)
    {
        $begining = substr(trim($query), 0, strlen(Scaffold::C_SELECT));
        if (strcasecmp($begining, Scaffold::C_SELECT) == 0) {
            return $this->customQuery($query, null, null, 'false');
        }

        return null;
    }

    /**
     * seteaza valorile fiecarui camp $key la $value, unde $key => $value sunt elemente din $arr, pentru
     * intrarile care au $field = $value
     */
    public function updateRows($arr, $field, $value)
    {
        $format = array(str_repeat('s', count($arr) + 1));
        $set_clause = $this->build_set_clause($arr);
        $where_clause = $this->build_where_clause($field);

        $query = sprintf(Scaffold::UPDATE_SQL, $this->table, $set_clause, $where_clause);

        print_r( $format);
        print_r(array(array_values($arr), $value));
        $stmt = $this->prepare_and_execute($query, $format, array_merge(array_values($arr), array($value)), "true");
        $stmt->close();
    }

    /**
     * insereaza in tabela o intrare cu campurile $key la valoarea $value, unde $key => $value sunt elemente
     * din $arr
     */
    public function insertRow($arr)
    {
        $format = array(str_repeat('s', count($arr)));
        list($columns, $params) = $this->build_insert_paranthesis($arr);

        $query = sprintf(Scaffold::INSERT_SQL, $this->table, $columns, $params);

        $stmt = $this->prepare_and_execute($query, $format, array_values($arr), "true");
        $stmt->close();

        return $this->db->insert_id;
    }

    /**
     * @param array $arr Associative array with the insert values
     * @return array Two strings with the parantheses content of the insert statement
     */
    public function build_insert_paranthesis($arr)
    {
        $columns = $this->merge_with_period(array_keys($arr));
        $params = $this->merge_with_period(array_fill(0, count($arr), "?"));
        return array($columns, $params);
    }

    /**
     * @param string $query custom query
     * @return array|null query results
     */
    public function customQuery($query)
    {
        return $this->run_get($query, null, null);
    }

    /**
     * Get the necessary rows from the database
     * @param array $value Wildcard values
     * @param array $format format for the wildcars
     * @param string $where_clause
     * @param string $orderby cause
     * @param string $direction cause
     * @param string $limit lower limit
     * @param string $show upper limmit
     * @return array|null Array of row or null if query didn't produce anything
     */
    protected function get_rows_from_db($value, $format, $where_clause, $orderby, $direction, $limit, $show)
    {
        /* Determining whitch optional params's are usable */
        list($orderby, $limit) = $this->build_aditional_params($orderby, $direction, $limit, $show);

        /* formating query based on column, sorting and limits */
        $query = sprintf(Scaffold::GET_ROWS_SQL, $this->table, $where_clause, $orderby, $limit);

        return $this->run_get($query, $format, $value, 'true');
    }

    /**
     * @param string $query Get Query Template
     * @param string $format bindable parameter format
     * @param string $value bindable parameter values
     * @param string $prepare indicates if the statement should be prepared
     * @return array|null Array of rows if they exist, or null
     */
    private function run_get($query, $format, $value, $prepare = 'false')
    {
        $stmt = $this->prepare_and_execute($query, $format, $value, $prepare);

        /* Binding results to column_headers */
        $row = $this->bind_table_header($stmt);

        /* running query */
        return $this->bind_results($stmt, $row);
    }
}
?>