<form action="scripts/attack.php" method="post">
    <p>
        From: <input type="text" id="claimIdPlanet" style="width: 30px" name="idPlanet1">
        To: <input type="text" id="claimIdPlanet" style="width: 30px" name="idPlanet2">
        Ships: <input type="text" id="claimIdPlanet" style="width: 30px" name="noShips">


        <input type="hidden" name="idUser" value="<?php echo AuthManager::getLoggedInUserId() ?>"/>
        <input type="hidden" name="idGame" value="<?php echo $game->id ?>"/>

        <a href="javascript:void(0);" class="join-style" onclick="submitForm(this)">ATTACK</a>
        <a href="scripts/change-inner-state.php" class="join-style">-Next-</a>
    </p>

</form>