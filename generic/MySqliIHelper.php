<?php
/**
 * User: Alexandru George Burghelea
 * Date: 03.03.2013
 * Time: 4:21 PM
 * For : PWeb 2013
 */

class MySqliIHelper
{
    protected $db;

    const C_EQUAL = "= ? ";
    const C_PERIOD = ", ";
    const C_AND = "AND ";
    const C_LIKE = " LIKE ? ";
    const C_SELECT = "SELECT";

    /**
     * Construct a helper
     * @param mysqli $db for interaction with the database
     */
    function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Prepare a MySQL query and execute-it. It doesn't fetch the results
     * it just stores them.
     *
     * @param string $query for MySQL Query (it may contain wildcards)
     * @param array|string $format  for parameter binding
     * @param array|string $value  for binding to the wildcards
     * @param string $needs_preparation indicates if to step over the preparation
     * @return mysqli_stmt ready for fetching
     */
    protected function prepare_and_execute($query, $format = array(), $value = array(), $needs_preparation = 'false')
    {
        $stmt = $this->db->prepare($query);
        if ($needs_preparation == 'true') {
            if (!is_array($value))
                $value = array($value);
            if (!is_array($format))
                $format = array($format);

            $params = array_merge($format, $value);

            call_user_func_array(array($stmt, 'bind_param'), $this->ref_values($params));
        }

        $stmt->execute();
        $stmt->store_result();
        return $stmt;
    }

    /**
     * Bind the values to the table headers
     * @param mysqli_stmt $stmt for query execution
     * @return array (hashtable) for storing a database line
     */
    protected function bind_table_header($stmt)
    {
        $row = array();
        $parameters = array();
        $meta = $stmt->result_metadata();
        while ($field = $meta->fetch_field()) {
            $parameters[] = & $row[$field->name];
        }
        call_user_func_array(array($stmt, 'bind_result'), $parameters);
        return $row;
    }

    /**
     * Bind the results to an hashtable (key = column name, value = line value)
     * @param mysqli_stmt $stmt from where to fetch the results
     * @param array $row where to temporary store the result
     * @return array|null Array of rows or null if they don't exist
     */
    protected function bind_results($stmt, $row)
    {
        $results = null;
        while ($stmt->fetch()) {
            $line = array();
            foreach ($row as $key => $val)
                $line[$key] = $val;
            $results[] = $line;
        }

        $stmt->close();
        return $results;
    }

    /**
     * Creates a WHERE SQL clause from an associative array (key = column name,
     * value = line value)
     * @param array $arr with the constraints
     * @return string representing the where clause
     */
    protected function build_where_clause($arr)
    {
        if (is_array($arr))
            return implode(self::C_LIKE . self::C_AND, array_keys($arr)) . self::C_LIKE;

        return $arr . self::C_LIKE;
    }

    protected function build_set_clause($arr)
    {
        if (is_array($arr))
            return implode(self::C_EQUAL . self::C_PERIOD, array_keys($arr)) . self::C_EQUAL;

        return $arr . self::C_EQUAL;
    }

    /**
     * @param array $arr  of words
     * @return string The words merged with <i>period</i>
     */
    protected function merge_with_period($arr)
    {
        if (is_array($arr))
            return implode(", ", $arr);

        return $arr;
    }

    /**
     * Form the trailing parameters
     * @param string $orderby value
     * @param string $direction value
     * @param string $limit value
     * @param string $show value
     * @return array with parameters formed for strings
     */
    protected function build_aditional_params($orderby = '', $direction = '', $limit = '', $show = '')
    {
        if ($orderby != '') {
            if (strcasecmp($direction, 'ASC') != 0 && strcasecmp($direction, 'DESC') != 0)
                $direction = 'ASC';
            $orderby = 'ORDER BY ' . $orderby . ' ' . $direction;
        }
        if ($limit != '') {
            $limit = 'LIMIT ' . $limit;
        }
        if ($show != '') {
            $limit .= ", " . $show . " ";
        }
        return array($orderby, $limit);
    }

    /**
     * Transform an array into arrays if the PHP version requires it
     * @param $arr
     * @return array
     */
    protected function ref_values(&$arr)
    {
        //Reference is required for PHP 5.3+
        if (strnatcmp(phpversion(), '5.3') >= 0) {
            $refs = array();
            foreach (array_keys($arr) as $key) {
                $refs[$key] = & $arr[$key];
            }
            return $refs;
        }

        return $arr;
    }
}
