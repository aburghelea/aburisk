<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */

require_once "../dao/actual/Planet_Game.php";
require_once "../dao/actual/Planet.php";
$userId = 3;
$gameId = 71;

$bonus = getShipBonus($userId, $gameId);

echo $bonus . "\n";
/**
 * @param $userId
 * @param $gameId
 * @return number
 */
function getShipBonus($userId, $gameId)
{
    $planetGameDao = new Planet_Game();
    $planetDao = new Planet();

    $allPlanets = $planetDao->getRowsByField("'1'", "1");
    $planetGalaxyMap = array();
    $bonusesMap = array();
    $bonus = 0;
    foreach ($allPlanets as $planet)
        $planetGalaxyMap[$planet->id] = $planet->containing_galaxy_id;

    $myPlanetGames = $planetGameDao->getRowsByArray(array("owner_id" => $userId, "game_id" => $gameId));
    foreach ($myPlanetGames as $planetGame) {
        $galaxyId = $planetGalaxyMap[$planetGame->planet_id];
        if (!array_key_exists($galaxyId, $bonusesMap)) {
            $bonusesMap[$galaxyId] = 0;
        }
        $bonusesMap[$galaxyId] += 1;
    }

    $oponentPlanetGame = $planetGameDao->getRowsByArray(array("owner_id not" => 1, "game_id" => 71));
    foreach ($oponentPlanetGame as $planetGame) {
        $galaxyId = $planetGalaxyMap[$planetGame->planet_id];
        if (array_key_exists($galaxyId, $bonusesMap))
            $bonusesMap[$galaxyId] = 0;

    }

    $bonusesMap = array_map('halfCeil', $bonusesMap);
    $bonus = array_sum($bonusesMap);
    return $bonus;
}



function halfCeil($nr)
{
    return intval(ceil($nr / 2));
}
?>