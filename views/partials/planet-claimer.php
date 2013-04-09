<form action="scripts/claim-planet.php" method="post">
    <p>
        Planet:
        <input type="text" id="idPlanet" style="width: 30px"
               name="idPlanet"
            >

        <input type="hidden" name="idUser"
               value="<?php echo AuthManager::getLoggedInUserId() ?>"/>
        <input type="hidden" name="idGame"
               value="<?php echo $game->id ?>"/>
        <!--                                    <div class="clearfix"></div>-->
        <a href="javascript:void(0);" class="join-style"
           onclick="submitForm(this)">Claim</a>
    </p>
</form>