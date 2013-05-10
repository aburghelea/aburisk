<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */

require_once dirname(__FILE__)."/recaptchalib.php";

function validcaptcha()
{
    $privatekey = "6Lc-C-ESAAAAAID_qYnEwOuz42mN0JH3cCyMmn5W";
    $resp = recaptcha_check_answer($privatekey,
        $_SERVER["REMOTE_ADDR"],
        $_POST["recaptcha_challenge_field"],
        $_POST["recaptcha_response_field"]);

    return $resp->is_valid;
}
?>