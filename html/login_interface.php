<!DOCTYPE HTML>
<html>
<?php require_once dirname(__FILE__) . "/head.php" ?>

<body onload="init()">
<div id="wrapper">
    <?php require_once dirname(__FILE__) . "/header.php" ?>
    <div id="page">
        <div id="content-center">
            <div id="formContainer">
                <form id="login" method="post" action="scripts/login.php">
                    <div class='formTitle'>Login</div>
                    <a href="javascript:void(0);" id="flipToRegister" class="flipLink">
                        Register?
                        <span class="icon-user"></span>
                    </a>
                    <input type="text" name="username" id="loginEmail" placeholder="Username"/>
                    <input type="password" name="password" id="loginPass" placeholder="Password"/>
                    <input type="submit" class="submit" name="submit" value="Login"/>
                </form>
                <form id="register" method="post" action="scripts/create-user.php">
                    <div class='formTitle'>Register</div>
                    <a href="javascript:void(0);" id="flipToLogin" class="flipLink">
                        Login?
                        <span class=" icon-hand-left"></span>
                    </a>
                    <input type="text" name="username" id="registerUsername" placeholder="Username"/>
                    <input type="password" name="password" id="RegisterPassword" placeholder="Password"/>
                    <input type="email" name="email" id="registerEmail" placeholder="Email"/>
                    <input type="submit"  class="submit" name="submit" value="Register"/>
                </form>
            </div>
        </div>

    </div>

    <?php require_once dirname(__FILE__) . "/footer.html" ?>
</div>
</body>
</html>