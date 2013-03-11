<?php
require_once("../database/Database.php");

$connection = Database::connect();
echo "Connection successful </br>";
$query = "SELECT * FROM users where id = 1";
$stmt = $connection->prepare($query);
$stmt->execute();
echo "Executing query</br>";
$stmt->bind_result($id, $username, $email, $password, $played_games, $won_games);
$stmt->fetch();
echo "Fetching results</br>";
$stmt->close();
echo $id . "&nbsp" . $username . "&nbsp" . $email . "&nbsp" . $password . "&nbsp" . $played_games . "&nbsp" . $won_games . "</br>";

?>