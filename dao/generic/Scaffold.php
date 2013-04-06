<?php

/**
 * User: Alexandru George Burghelea
 * Date: 02.03.2013
 * Time: 11:56
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/../../inc/IScaffold.inc.php";
require_once dirname(__FILE__) . "/../database/Database.php";
require_once dirname(__FILE__) . "/../generic/MySqliIHelper.php";

class Scaffold extends MySqliIHelper implements IScaffold
{
    private $table;

    const CUSTOM_SELECT_SQL = "SELECT * FROM %s";
    const GET_ROWS_SQL = "SELECT * FROM %s WHERE %s %s %s";
    const INSERT_SQL = "INSERT INTO %s (%s) VALUES (%s)";
    const UPDATE_SQL = "UPDATE %s SET %s WHERE %s";

    public function __construct($table)
    {
        parent::__construct(Database::connect());
        $this->table = mysqli_real_escape_string($this->db, $table);
    }

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
    public function getRowsbyField($field, $value, $orderby = '', $direction = 'ASC', $limit = '', $show = '')
    {
        /* Creating empty results array */
        $format = "s";
        $where_clause = $this->build_where_clause($field);

        return $this->get_rows_from_db($value, $format, $where_clause, $orderby, $direction, $limit, $show);

    }

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
    public function getRowsByArray($arr, $orderby = '', $direction = 'ASC', $limit = '', $show = '')
    {
        $format = array(str_repeat('s', count($arr)));
        $value = array_values($arr);
        $where_clause = $this->build_where_clause($arr);

        return $this->get_rows_from_db($value, $format, $where_clause, $orderby, $direction, $limit, $show);
    }

    /**
     * Extracts the entries from the database folowing the SQL query sent as parameter
     * @param $query
     * @return array|null an associative array or null if now entry has been found
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
     * Sets the value of each $key to $value , where $key => $value are elements
     * of $arr, for the entries where $field = $value
     * @param $arr
     * @param $field
     * @param $value
     */
    public function updateRows($arr, $field, $value)
    {
        $format = array(str_repeat('s', count($arr) + 1));
        $set_clause = $this->build_set_clause($arr);
        $where_clause = $this->build_where_clause($field);

        $query = sprintf(Scaffold::UPDATE_SQL, $this->table, $set_clause, $where_clause);

        $stmt = $this->prepare_and_execute($query, $format, array_merge(array_values($arr), array($value)), "true");
        $stmt->close();
    }

    /**
     * Sets the value of each $key to $value , where $key => $value are elements
     * of $arr
     * @param $arr
     * @return mixed
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