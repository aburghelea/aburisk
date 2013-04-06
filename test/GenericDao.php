<?php

/**
 * User: Alexandru George Burghelea
 * Date: 10.03.2013
 * Time: 11:37 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . '/../generic/Scaffold.php';

/**
 * Generic CRUD/domain
 * It has the same structure as IScaffold, but it delegates it's methods to a Scaffold object
 */
class GenericDao
{

    protected static $TABLE_NAME;
    private $scaffold;


    function __construct()
    {
        $this->scaffold = new Scaffold('games');
    }

    public function customQuery($query)
    {
        return $this->scaffold->customQuery($query);
    }

}
$g = new GenericDao();
//$g = new Scaffold('games');
//$gs = $g->customQuery("select * from games where state not like 'GAME_END'  and id not in (select game_id from users_games where user_id = 2)");
$gs = $g->customQuery("select * from games where id =1");
var_dump(current($gs));
?>

