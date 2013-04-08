<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/../session/GameManager.php";
require_once dirname(__FILE__) . "/../game/GameEngine.php";
GameManager::advanceStageIfNecessary(true);
header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/game.php');
exit();

?>