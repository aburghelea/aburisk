<?php
/**
 * Created by JetBrains PhpStorm.
 * User: iceman
 * Date: 3/3/13
 * Time: 4:21 PM
 * To change this template use File | Settings | File Templates.
 */
class MySqliIHelper
{

    private $db;

    const C_AND = "AND ";
    const C_LIKE = " LIKE ? ";
    const C_SELECT = "SELECT";

    function __construct($db)
    {
        $this->db = $db;
    }

    protected function execute_prepared($query, $format, $value, $needs_preparation = 'true')
    {
        $stmt = $this->db->prepare($query);

        if ($needs_preparation == 'true') {
            echo "preparing <br/>";
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

    protected function bind_table_header($stmt)
    {
        $parameters = array();
        $meta = $stmt->result_metadata();
        while ($field = $meta->fetch_field()) {
            $parameters[] = & $row[$field->name];
        }
        call_user_func_array(array($stmt, 'bind_result'), $parameters);
        return $row;
    }

    protected function bind_results($stmt, $row)
    {
        $results = null;
        while ($stmt->fetch()) {
            $line = array();
            foreach ($row as $key => $val)
                $line[$key] = $val;
            $results[] = $line;
        }

        return $results;
    }

    protected function build_where_clause($arr)
    {
        if (is_array($arr))
            return implode(self::C_LIKE . self::C_AND, array_keys($arr)) . self::C_LIKE;

        return $arr . self::C_LIKE;
    }

    protected function build_aditional_params($orderby, $direction, $limit)
    {
        if ($orderby != '') {
            if (strcasecmp($direction, 'ASC') != 0 && strcasecmp($direction, 'DESC') != 0)
                $direction = 'ASC';
            $orderby = 'ORDER BY ' . $orderby . ' ' . $direction;
        }
        if ($limit != '') {
            $limit = 'LIMIT ' . $limit;
            return array($orderby, $limit);
        }
        return array($orderby, $limit);
        /* TODO: De tinut cont de parametrul $SHOW  */
    }

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
