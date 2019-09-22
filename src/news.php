<?php
include "./guard.php";
include "./database.php";

function get_title($url) {
  $page = file_get_contents($url);
  if (!$page) {
    return $url;
  }
  preg_match("/<title>(.*)<\/title>/siU", $page, $matches);
  $title = $matches[1];
  if (!$title) {
    return $url;
  }
  return $title;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $action = $_POST['action'];
  if ($action == 'post_story') {
    $url = $_POST['url'];
    $title = get_title($url);
    $stmt = $mysqli->prepare("insert into stories (link, poster, title) values (?, ?, ?)");
    $stmt->bind_param("sds", $url, $user, $title);
    $stmt->execute();
    $stmt->close();
  } elseif ($action == 'delete_story') {
    $storyId = $_POST['story_id'];
    $stmt = $mysqli->prepare("delete from stories where storyId=? and poster=?");
    $stmt->bind_param("ds", $storyId, $user);
    $stmt->execute();
    $stmt->close();
  }
}

$stmt = $mysqli->prepare("select stories.title, users.username, users.userId, stories.storyId from stories, users where users.userId = stories.poster order by stories.storyId desc");
$stmt->execute();
$stmt->bind_result($title, $poster, $posterId, $storyId);
?>
<!DOCTYPE html>
<html>
  <?php include "./header.php" ?>
  <body>
    <?php if ($user) { ?>
    <a href="./login.php">Logout</a><a href="./account.php">Account</a>
    <form action="./news.php" method="POST">
      <label>URL:</label><input type="text" name="url">
      <input type="hidden" name="action" value="post_story">
      <input type="submit" value="Post Story">
    </form>
    <?php } else { ?>
      <a href="./login.php">Log In/Register</a>
    <?php } ?>
    <ul>
      <?php
      while($stmt->fetch()) {
        echo "<li><a href=\"./view.php?sid=$storyId\">$title</a> by $poster";
        if ($posterId == $user) {
          echo "<form action=./news.php method=POST><input type=hidden name=story_id value=$storyId><input type=hidden name=action value=delete_story><input type=submit value=Delete></form>";
        }
        echo "</li>";
      }
      ?>
    </ul>
  </body>
</html>
