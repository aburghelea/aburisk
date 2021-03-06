<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/../session/GameManager.php";
require_once dirname(__FILE__) . "/../game/GameEngine.php";
GameManager::advanceStageIfNecessary(true);
GameManager::getGameEngine()->signalUpdate();
$rtn['status'] = 'SUCCESS';
$rtn['owner'] = AuthManager::getLoggedInUserId();
echo json_encode($rtn);

?>