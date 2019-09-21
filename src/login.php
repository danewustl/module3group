<?php
// Cleanup session variables
session_start();
$_SESSION['loggedIn'] = false;
unset($_SESSION['user']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Log in
  require "./database.php";
  $username = $_POST['user'];
  $password = $_POST['password'];

  $stmt = $mysqli->prepare("select userId, userPass from users where username=? limit 1");
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $stmt->bind_result($uid, $password_hash);
  $stmt->fetch();
  if (password_verify($password, $password_hash)) {
    $_SESSION['loggedIn'] = true;
    $_SESSION['user'] = $uid;
    header("Location: ./news.php");
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <?php include "./header.php" ?>
  <body>
    <h1 class=login>Welcome to your news website. Please enter a valid user to be able to view files.</h1>
    <form action=<?php echo $_SERVER["PHP_SELF"];?> method="POST">
      <label>Enter username: <input type="text" name="user"> </label>
      <label>Enter password: <input type="password" name="password"> </label>
      <input type="submit" value="Login">
    </form>
    <form action="./signup.php" method="POST">
      <label>Enter username: <input type="text" name="user"> </label>
      <label>Enter password: <input type="password" name="password"> </label>
      <input type="submit" value="Signup">
    </form>
  </body>
</html>
