<div id="header">
    <div id="logo">
        <h1><a href="#">Aburisk</a></h1>

        <p>A game by <a href="http://about.me/alexandru.burghelea" target="_blank">Alexandru Burghelea</a></p>
    </div>
    <div id="menu">
        <ul>
            <li><a href="/aburisk" accesskey="h" title="">Homepage</a></li>
            <?php if (AuthManager::getLoggedInUserId()) { ?>
                <li><a href="/aburisk/gameslist.php" accesskey="g" title="">Games List</a></li>
                <li><a href="/aburisk/game.php" accesskey="g" title="">Current Game</a></li>
            <?php } ?>
            <?php require_once dirname(__FILE__) . "/user_info.php"?>
        </ul>
    </div>
</div>