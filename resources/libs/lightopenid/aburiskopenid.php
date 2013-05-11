<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/openid.php";
require_once dirname(__FILE__) . "/../../../dao/actual/User.php";
require_once dirname(__FILE__) . "/../../../session/AuthManager.php";
require_once dirname(__FILE__) . "/../../../session/GameManager.php";

function getServer()
{
    return $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER['HTTP_HOST'];
}


function getOpenIdButton()
{
    $server = getServer();
    $openid = new LightOpenID($server);
    $openid->identity = 'https://www.google.com/accounts/o8/id';
    $openid->required = array('contact/email');
    $openid->returnUrl = $server . $_SERVER['CONTEXT_PREFIX'] . '/scripts/open_login.php';
    ?>

    <a class="zocial google" href="<?php echo $openid->authUrl() ?>">Login with Google</a>
<?php
}

function handleLogin()
{
    $server = getServer();
    $openid = new LightOpenID($server);
    if ($openid->mode) {
        if ($openid->mode == 'cancel') {
            header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/login.php');
            exit();
        } elseif ($openid->validate()) {
            $data = $openid->getAttributes();
            $email = $data['contact/email'];
            echo "Email : $email <br>";
            $user = new User();
            $exists = User::alreadyExists($email);
            if (!$exists) {
                $exists = User::register($email, $email, $email);
            } else {
                loginUserByEmail($email);
                header('Location: ' . $_SERVER['CONTEXT_PREFIX'] );
                exit();
            }
        } else {
            header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/login.php?login_error=true');
            exit();
        }
    } else {
        header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/login.php');
        exit();
    }
}

function loginUserByEmail($email) {
    $userDao = new User();
    $user = $userDao->getRowsByField("email",$email);
    if (!$user)
        return;
    $user = $user[0];
    AuthManager::userId($user->getId());
    GameManager::initShips();
    GameManager::updateEngagedGame($user->getId());
}

?>