<?php
include "./guard.php";
include "./database.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $stmt = $mysqli->prepare("update users set username = ? where userId=?");
  $stmt->bind_param("sd", $name, $user);
  $stmt->execute();
  $stmt->close();
}
$stmt = $mysqli->prepare("select username from users where userId=? limit 1");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html>
  <?php include "./header.php" ?>
  <body>
    <h1 class="login">Account Information</h1>
    <h1 class="view">Username: <?php echo $username; ?></h1>
    <form action="./account.php" method="POST">
      <label>Change username:</label>
      <input type="text" name="name">
      <input type="submit" value="Change">
    </form>
    <br>
    <h1 class="view">
    <form action="./news.php" method="POST">
      <input type="submit" value="Return to homepage">
    </form>
</h1>
  </body>
</html>
