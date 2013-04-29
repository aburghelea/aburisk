<form action="scripts/attack.php" method="post">
    <p>
        Attacker :<input type="text" id="idPlanet1" style="width: 30px; height: auto" name="idPlanet1">
    </p>

    <p>
        Attacked:<input type="text" id="idPlanet2" style="width: 30px; height: auto" name="idPlanet2">
    </p>

    <p>
        Ships: <input type="number" id="noShips" style="width: 50px; height: auto" name="noShips">
    </p>


    <div class="clearfix" style="margin-top: 10px">
        <a href="scripts/change-inner-state.php" class="button-style" style="margin-top: 0 !important;">&gt;&gt;End
            turn</a>

        <a href="javascript:void(0);" class="join-style" onclick="submitForm(this)">ATTACK</a>
    </div>
</form>