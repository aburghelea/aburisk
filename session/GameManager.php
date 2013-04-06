<?php
/**
 * Created by JetBrains PhpStorm.
 * User: iceman
 * Date: 4/6/13
 * Time: 10:12 PM
 * To change this template use File | Settings | File Templates.
 */
if (session_status() == PHP_SESSION_NONE)
    session_start();

class GameManager {

    public static function getGame() {
        $isInGame = isSet($_SESSION['game_id']);

        return $isInGame ? $_SESSION['game_id']  : false;
    }

    public static function setGame($game = null)
    {
        if ($game == null)
            unset($_SESSION['game_id']);
        else
            $_SESSION['game_id'] = $game;
    }
}