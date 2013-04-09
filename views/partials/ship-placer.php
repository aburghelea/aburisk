<form action="scripts/deploy-ship.php" method="post">
    <p>
        Deploy to: <input type="text" id="idPlanet" style="width: 30px" name="idPlanet">

        <input type="hidden" name="idUser" value="<?php echo AuthManager::getLoggedInUserId() ?>"/>
        <input type="hidden" name="idGame" value="<?php echo $game->id ?>"/>
    </p>

    <p style="margin-top: 10px">
        <a href="scripts/change-inner-state.php" class="button-style" style="margin-top: 0 !important;">
            &gt;&gt;Attack</a>
        <a href="javascript:void(0);" class="join-style"
           onclick="submitForm(this)">Deploy</a>
    </p>

</form>