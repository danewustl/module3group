<?php
include "./guard.php";
include "./database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $action = $_POST['action'];
  if ($action == 'post_story') {
    $url = $_POST['url'];
    $stmt = $mysqli->prepare("insert into stories (link, poster) values (?, ?)");
    $stmt->bind_param("sd", $url, $user);
    $stmt->execute();
    $stmt->close();
  } elseif ($action == 'delete_story') {
    $storyId = $_POST['story_id'];
    $stmt = $mysqli->prepare("delete from stories where storyId=?");
    $stmt->bind_param("d", $storyId);
    $stmt->execute();
    $stmt->close();
  }
}

$stmt = $mysqli->prepare("select stories.link, users.username, users.userId, stories.storyId from stories, users where users.userId = stories.poster order by stories.storyId desc");
$stmt->execute();
$stmt->bind_result($link, $poster, $posterId, $storyid);
?>
<!DOCTYPE html>
<html>
  <?php include "./header.php" ?>
  <body>
    <form action="./news.php" method="POST">
      <label>URL:</label><input type="text" name="url">
      <input type="hidden" name="action" value="post_story">
      <input type="submit" value="Post Story">
    </form>
    <ul>
      <?php
      while($stmt->fetch()) {
        echo "<li><a href=\"$link\">$link</a> by $poster";
        if ($posterId == $user) {
          echo "<form action=./news.php method=POST><input type=hidden name=story_id value=$storyid><input type=hidden name=action value=delete_story><input type=submit value=Delete></form>";
        }
        echo "</li>";
      }
      ?>
    </ul>
  </body>
</html>
