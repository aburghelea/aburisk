<!DOCTYPE HTML>
<html>
<?php require_once dirname(__FILE__) . "/head.php";

require_once dirname(__FILE__) . "/../resources/libs/recaptcha/aburiskcaptcha.php";
require_once dirname(__FILE__) . "/../resources/libs/lightopenid/aburiskopenid.php";

?>
<script>
    var RecaptchaOptions = {
        theme: 'white',
        tabindex: 2
    };
</script>

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

            <div id="formContainer">
                <form id="login" class="login" method="post" action="scripts/login.php">
                    <div class='formTitle'>Login</div>
                    <div id="captcha_unflipped">

                        <div id="recaptcha_container">
                            <?php echo recaptcha_get_html(publickey); ?>
                        </div>
                    </div>
                    <p>
                        <input type="email" name="username" placeholder="Email"/>
                    </p>

                    <p>
                        <input type="password" name="password" placeholder="Password"/>
                    </p>


                    <div class="login-submit">
                        <button type="submit" class="login-button">Login</button>
                    </div>
                    <div>
                        <div class="googleLogin" style="float: left">

                            <?php getOpenIdButton() ?>
                        </div>
                        <a href="javascript:void(0);" id="flipToRegister" class="flipLink">
                            Register?
                        </a>
                    </div>
                </form>
                <form id="register" class="login" method="post" action="scripts/create-user.php">
                    <div class='formTitle'>Register</div>

                    <div id="captcha_flipped">

                    </div>

                    <p>
                        <input type="email" name="username" placeholder="Email"/>
                    </p>

                    <p>
                        <input type="password" name="password" placeholder="Password"/>
                    </p>

                    <div class="login-submit">
                        <button type="submit" class="login-button">Register</button>
                    </div>
                    <div>
                        <div class="googleLogin" style="float: left">

                            <?php getOpenIdButton() ?>
                        </div>
                        <a href="javascript:void(0);" id="flipToLogin" class="flipLink">
                            Login?
                        </a>
                    </div>


                </form>
            </div>
        </div>

    </div>

    <?php require_once dirname(__FILE__) . "/footer.html" ?>
</div>
</body>
</html>