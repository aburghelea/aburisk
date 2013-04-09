<form action="scripts/attack.php" method="post">
    <p>
        From: <input type="text" id="idPlanet1" style="width: 30px" name="idPlanet1">
        To: <input type="text" id="idPlanet2" style="width: 30px" name="idPlanet2">
        Ships: <input type="text" id="noShips" style="width: 30px" name="noShips">


        <input type="hidden" name="idUser" value="<?php echo AuthManager::getLoggedInUserId() ?>"/>
        <input type="hidden" name="idGame" value="<?php echo $game->id ?>"/>
    </p>

    <div class="clearfix">
        <a href="scripts/change-inner-state.php" class="button-style" style="margin-top: 0 !important;">End turn</a>

        <a href="javascript:void(0);" class="join-style" onclick="submitForm(this)">ATTACK</a>
    </div>
</form>