<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */

require_once dirname(__FILE__)."/../logger/Aburlog.php";

$log   = Aburlog::getInstance(dirname(__FILE__), Aburlog::DEBUG);
$args1 = array('a' => array('b' => 'c'), 'd');
$args2 = NULL;

$log->logInfo('Info Test');
$log->logWarn('Warn Test');
$log->logError('Error Test');

$log->logInfo('Testing passing an array or object', $args1);
$log->logWarn('Testing passing a NULL value', $args2);