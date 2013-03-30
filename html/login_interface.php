<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Aburisk</title>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700" rel="stylesheet" type="text/css">

    <link href="theme/css/default.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="theme/css/styles.css" rel="stylesheet" type="text/css" media="all"/>
    <script src="theme/js/script.js"></script>

</head>
<body onload="init()">
<div id="wrapper">
    <div id="header">
        <div id="logo">
            <h1><a href="#">Aburisk</a></h1>

            <p>A game by <a href="http://about.me/alexandru.burghelea" target="_blank">Alexandru Burghelea</a></p>
        </div>
        <div id="menu">
            <ul>
                <li><a href="#" accesskey="1" title="">Homepage</a></li>
            </ul>
        </div>
    </div>
    <div id="page">
        <div id="content-center">
            <div id="formContainer">
                <form id="login" method="post" action="scripts/login.php?action=login">
                    <div class='formTitle'>Login</div>
                    <a href="javascript:void(0);" id="flipToRegister" class="flipLink">Register?</a>
                    <input type="text" name="username" id="loginEmail" placeholder="Username"/>
                    <input type="password" name="password" id="loginPass" placeholder="Password"/>
                    <input type="submit" name="submit" value="Login"/>
                </form>
                <form id="register" method="post" action="scripts/login.php?action=register">
                    <div class='formTitle'>Register</div>
                    <a href="javascript:void(0);" id="flipToLogin" class="flipLink">Login?</a>
                    <input type="text" name="registerUsername" id="registerUsername" placeholder="Username"/>
                    <input type="text" name="registerPassword" id="RegisterPassword" placeholder="Password"/>
                    <input type="text" name="registerEmail" id="registerEmail" placeholder="Email"/>
                    <input type="submit" name="submit" value="Register"/>
                </form>
            </div>
        </div>

    </div>

    <div id="footer">
        <!--        <p>Copyright (c) 2013 Sitename.com. All rights reserved. Design by <a-->
        <!--                href="http://www.freecsstemplates.org">FCT</a>. Photos by <a href="http://fotogrph.com/">Fotogrph</a>.-->
        <!--        </p>-->
    </div>
</div>
</body>
</html>