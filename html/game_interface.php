<!DOCTYPE HTML>
<html>
<?php require_once "head.html";

require_once dirname(__FILE__) . "/../dao/Planet.php";
$planetDao = new Planet();
$planetsJSON = json_encode($planetDao->getRowsByField('"1"', '1'));
?>

<body>
<div id="wrapper">
    <?php require_once "header.php" ?>
    <div id="page">
        <div id="content">
            <object id='map' onload='initMap(<?php echo $planetsJSON?>)' type="image/svg+xml" width="750" height="421" data="html/map.svg"></object>
        </div>
        <div id="sidebar">
            <div id="tbox1">
                <h2>Mauris vulputate dolor</h2>
                <ul class="style2">
                    <li class="first">
                        <h3><a href="#">Maecenas luctus lectus</a></h3>

                        <p><a href="#">Quisque dictum integer nisl risus, sagittis convallis, rutrum id, congue, and
                                nibh.</a></p>
                    </li>
                    <li>
                        <h3><a href="#">Integer gravida nibh</a></h3>

                        <p><a href="#">Quisque dictum integer nisl risus, sagittis convallis, rutrum id, congue, and
                                nibh.</a></p>
                    </li>
                    <li>
                        <h3><a href="#">Fusce ultrices fringilla</a></h3>

                        <p><a href="#">Quisque dictum integer nisl risus, sagittis convallis, rutrum id, congue, and
                                nibh.</a></p>
                    </li>
                    <li>
                        <h3><a href="#">Nulla luctus eleifend</a></h3>

                        <p><a href="#">Quisque dictum integer nisl risus, sagittis convallis, rutrum id, congue, and
                                nibh.</a></p>
                    </li>
                </ul>
            </div>
        </div>
    </div>


    <?php require_once "footer.html" ?>
</div>
</body>
</html>