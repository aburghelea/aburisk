<form action="scripts/deploy-ship.php" method="post">
    <p>
        Deploy to: <input type="text" id="idPlanet" style="width: 30px" name="idPlanet">

        <input type="hidden" name="idUser" value="<?php echo AuthManager::getLoggedInUserId() ?>"/>
        <input type="hidden" name="idGame" value="<?php echo $game->id ?>"/>

        <a href="javascript:void(0);" class="join-style" onclick="submitForm(this)">Deploy</a>
        <a href="scripts/change-inner-state.php" class="join-style">-Atack-</a>
    </p>

</form>