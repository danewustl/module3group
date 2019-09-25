<?php
require "database.php";
$user = htmlspecialchars($_POST['user']);
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);


$stmt = $mysqli->prepare("insert into users (username, userPass) values (?, ?)");
$stmt->bind_param('ss', $user, $password);
$stmt->execute();
if(!$stmt) {
  printf("Bad query: %s", $mysqli->error);
  exit;
}
// TODO be sure to check for duplicate usernames
$stmt->close();
header("Location: ./login.php");
?>
