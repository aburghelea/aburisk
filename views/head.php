<?php require_once dirname(__FILE__) . "/../session/GameManager.php" ?>
<?php require_once dirname(__FILE__) . "/../session/AuthManager.php" ?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Aburisk</title>


    <link href="resources/css/default.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="resources/css/map.css" rel="stylesheet" type="text/css" media="all"/>
    <script src="resources/js/script.js"></script>
    <script src="resources/js/players.js"></script>
    <script src="resources/js/map.js"></script>
    <script src="resources/js/game.js"></script>
    <?php if (AuthManager::getLoggedInUserId()) { ?>
        <script>
            ABURISK.players.setCurrent(<?php echo GameManager::getCurrentPlayerId() ?>);
        </script>
    <?php } ?>
</head>