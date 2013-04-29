<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/12/13
 * Time: 10:29 PM
 * For : PWeb 2013
 */
class ShipAttackJudge
{

    /**
     * @param int $attacker_ships number of attacking ships
     * @param int $defender_ships number of defending ships
     * @param int $total_atacking number of ships with witch is attacking
     * @return int|array -1 if the attack is not possible, (remaining ships on attack, remaining ships on defence, conquering_ships)
     */
    public static function judge($attacker_ships, $defender_ships, $total_atacking)
    {
        $no_att_dice = self::attacking(min($attacker_ships, $total_atacking+1));
        $no_def_dice = self::defending($defender_ships);

        $dice = min($no_att_dice, $no_def_dice);
        if ($dice <= 0)
            return -1;

        $attacking_dice = array();
        $defending_dice = array();
        for ($i = 0; $i < $dice; $i++) {
            $attacking_dice[] = rand(1, 6);
            $defending_dice[] = rand(1, 6);
        }
        rsort($attacking_dice);
        rsort($defending_dice);

        $attack_casualties = 0;
        $defend_casualties = 0;
        for ($i = 0; $i < $dice; $i++) {

            if ($attacking_dice[$i] > $defending_dice[$i])
                $defend_casualties++;
            else
                $attack_casualties++;
        }
        $attacker_ships = max(1, ($attacker_ships - $attack_casualties));
        $defender_ships = max(0, ($defender_ships - $defend_casualties));
        $conquering_ships = $defender_ships == 0 ? min($total_atacking, ($no_att_dice - $attack_casualties)) : 0;
        if ($conquering_ships > 0) {
            $conquering_ships = $conquering_ships < $attacker_ships ? $conquering_ships : ($attacker_ships - 1);
            $attacker_ships -= $conquering_ships;
        }

        return array("A" => $attacker_ships, "D" => $defender_ships, "C" => $conquering_ships);

    }

    private static function attacking($ships)
    {
        if ($ships < 2)
            return -1;
        else if ($ships == 2)
            return 1;
        else if ($ships == 3)
            return 2;

        return 3;
    }

    private static function defending($ships)
    {
        if ($ships < 1)
            return -1;
        else if ($ships == 1)
            return 1;

        return 2;
    }
}

?>