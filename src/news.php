<?php
include "./guard.php";
include "./database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $url = $_POST['url'];
  $stmt = $mysqli->prepare("insert into stories (link, poster) values (?, ?)");
  $stmt->bind_param("sd", $url, $user);
  $stmt->execute();
  $stmt->close();
}

$stmt = $mysqli->prepare("select stories.link, users.username from stories, users where users.userId = stories.poster order by stories.storyId desc");
$stmt->execute();
$stmt->bind_result($link, $poster);
?>
<!DOCTYPE html>
<html>
  <?php include "./header.php" ?>
  <body>
    <form action="./news.php" method="POST">
      <label>URL:</label><input type="text" name="url">
      <input type="submit" value="Post Story">
    </form>
    <ul>
      <?php
      while($stmt->fetch()) {
        echo "<li><a href=\"$link\">$link</a> by $poster</li>";
      }
      ?>
  </body>
</html>
