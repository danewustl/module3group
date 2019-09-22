<?php
include "./guard.php";
include "./database.php";

$storyId=$_GET['sid'];

$stmt = $mysqli->prepare("select stories.title, users.username, stories.link from stories, users where users.userId = stories.poster and stories.storyId = ?");
$stmt->bind_param('d', $storyId);
$stmt->execute();
$stmt->bind_result($title, $poster, $url);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $action = $_POST['action'];
  if ($action == 'post_comment') {
    $comment = $_POST['comment'];
    $stmt = $mysqli->prepare("insert into comments (comment, storyId, commenter) values (?, ?, ?)");
    $stmt->bind_param("sdd", $comment, $storyId, $user);
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
  $stmt2 = $mysqli->prepare("select comments.comment, users.username, users.userId from comments, users where users.userId = comments.commenter and comments.storyId=? order by comments.commentId");
  $stmt2->bind_param("d", $storyId);
  $stmt2->execute();
  $stmt2->bind_result($comment, $commenter, $commenterId);
?>
<!DOCTYPE html>
<html>
  <?php include "./header.php" ?>
  <body>
    <?php echo "<h1>$title by $poster</h1>";
    echo " <a href=$url> $url </a>"; ?>
    <form action=<?php echo "./view.php?sid=$storyId";?> method="POST">
      <textarea rows="4" cols="50" name="comment"></textarea>
      <input type="hidden" name="action" value="post_comment">
      <input type="submit" value="Post Comment">
    </form>

    <?php
      while($stmt2->fetch()) {
        echo "<li>$commenter says: $comment";
        echo "</li>";
      }
      ?>
  </body>
</html>
