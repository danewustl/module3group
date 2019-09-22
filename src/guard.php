<?php
// Make sure the user is logged in, and then set the global user
session_start();
global $user;
if ($_SESSION['loggedIn']) {
  $user = $_SESSION['user'];
} else {
  $user = null;
}
?>
