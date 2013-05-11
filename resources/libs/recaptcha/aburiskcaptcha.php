<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */

require_once dirname(__FILE__)."/recaptchalib.php";

const privatekey = "6Lc-C-ESAAAAAID_qYnEwOuz42mN0JH3cCyMmn5W";
const publickey = "6Lc-C-ESAAAAABlMt1Kx1c5UasGReB0jiOScaeMj";

function validcaptcha()
{
    $resp = recaptcha_check_answer(privatekey,
        $_SERVER["REMOTE_ADDR"],
        $_POST["recaptcha_challenge_field"],
        $_POST["recaptcha_response_field"]);

    return $resp->is_valid;
}
?>