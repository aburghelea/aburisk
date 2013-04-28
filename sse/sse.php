<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/../session/GameManager.php";
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

function sendData($id)
{
    echo "id: $id" . PHP_EOL;
    echo "data: You should update" . PHP_EOL;
    echo PHP_EOL;
    ob_flush();
    flush();
}

function sendNo($id)
{
    echo "id: $id" . PHP_EOL;
    echo "data: Do not update" . PHP_EOL;
    echo PHP_EOL;
    ob_flush();
    flush();
}

$serverTime = time();

if (GameManager::isModified()) {
    sendData($serverTime);
} else {
    sendNo($serverTime);
}