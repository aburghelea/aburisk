<!DOCTYPE HTML>
<html>
<?php require_once dirname(__FILE__) . "/head.php";

require_once dirname(__FILE__) . "/../resources/libs/recaptcha/aburiskcaptcha.php";
require_once dirname(__FILE__) . "/../resources/libs/lightopenid/aburiskopenid.php";

?>

<body onload="init()">
<div id="wrapper">
    <?php require_once dirname(__FILE__) . "/header.php" ?>
    <div id="page">
        <div id="content-center">
            <?php
            if (isset($_GET["login_error"])) {
                ?>
                <div id="error_msg" class="error_msg">User or password do not exist</div>
                <script>
                    removeAfterTime("error_msg");
                </script>
            <?php
            }
            else if (isset($_GET["registered"]) && $_GET["registered"] == "false") {
                ?>
                <div id="error_msg" class="error_msg">Username taken</div>
                <script>
                    removeAfterTime("error_msg");
                </script>
            <?php
            }else if (isset($_GET["captcha"]) && $_GET["captcha"] == "captcha") {
                ?>
                <div id="error_msg" class="error_msg">Wrong captcha</div>
                <script>
                    removeAfterTime("error_msg");
                </script>
            <?php
            }
            ?>
            <?php getOpenIdButton() ?>
            <div id="formContainer">
                <form id="login" method="post" action="scripts/login.php">
                    <div class='formTitle'>Login</div>
                    <a href="javascript:void(0);" id="flipToRegister" class="flipLink">
                        Register?
                        <span class="icon-user"></span>
                    </a>

                    <div class="aligncenter">
                        <input type="email" name="username" placeholder="Email"/>
                    </div>
                    <div class="aligncenter">
                        <input type="password" name="password" placeholder="Password"/>
                    </div>
                    <div id="captcha_unflipped">

                        <div class="aligncenter" style="width: 318px !important;" id="recaptcha_container">
                            <?php echo recaptcha_get_html(publickey); ?>
                        </div>
                    </div>
                    <div class="aligncenter">
                        <input type="submit" class="submit" name="submit" value="Login"/>
                    </div>
                </form>
                <form id="register" method="post" action="scripts/create-user.php">
                    <div class='formTitle'>Register</div>
                    <a href="javascript:void(0);" id="flipToLogin" class="flipLink">
                        Login?
                        <span class=" icon-arrow-left"></span>
                    </a>

                    <div class="aligncenter">
                        <input type="email" name="username" id="registerUsername" placeholder="Email"/>
                    </div>
                    <div class="aligncenter">
                        <input type="password" name="password" id="RegisterPassword" placeholder="Password"/>
                    </div>
                    <div id="captcha_flipped">

                    </div>
                    <div class="aligncenter">
                        <input type="submit" class="submit" name="submit" value="Register"/>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <?php require_once dirname(__FILE__) . "/footer.html" ?>
</div>
</body>
</html>