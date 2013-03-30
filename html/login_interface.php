<!DOCTYPE HTML>
<html>
<?php include_once dirname(__FILE__)."/head.html" ?>

<body onload="init()">
<div id="wrapper">
    <?php include_once dirname(__FILE__)."/header.php" ?>
    <div id="page">
        <div id="content-center">
            <div id="formContainer">
                <form id="login" method="post" action="scripts/login.php">
                    <div class='formTitle'>Login</div>
                    <a href="javascript:void(0);" id="flipToRegister" class="flipLink">Register?</a>
                    <input type="text" name="username" id="loginEmail" placeholder="Username"/>
                    <input type="password" name="password" id="loginPass" placeholder="Password"/>
                    <input type="submit" name="submit" value="Login"/>
                </form>
                <form id="register" method="post" action="scripts/create-user.php">
                    <div class='formTitle'>Register</div>
                    <a href="javascript:void(0);" id="flipToLogin" class="flipLink">Login?</a>
                    <input type="text" name="username" id="registerUsername" placeholder="Username"/>
                    <input type="password" name="password" id="RegisterPassword" placeholder="Password"/>
                    <input type="email" name="email" id="registerEmail" placeholder="Email"/>
                    <input type="submit" name="submit" value="Register"/>
                </form>
            </div>
        </div>

    </div>

    <?php include_once dirname(__FILE__)."/footer.html" ?>
</div>
</body>
</html>