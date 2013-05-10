<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/../session/GameManager.php";
require_once dirname(__FILE__) . "/../game/GameEngine.php";
$rtn['status'] = 'SUCCESS';
$rtn['owner'] = AuthManager::getLoggedInUserId();
GameManager::advanceStage();
GameManager::getGameEngine()->signalUpdate();
echo json_encode($rtn);

?>