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
    $comment = htmlspecialchars($_POST['comment']);
    $stmt = $mysqli->prepare("insert into comments (comment, storyId, commenter) values (?, ?, ?)");
    $stmt->bind_param("sdd", $comment, $storyId, $user);
    $stmt->execute();
    $stmt->close();
  } elseif ($action == 'delete_comment') {
    $commentId = $_POST['comment_id'];
    $stmt = $mysqli->prepare("delete from comments where commentId=? and commenter=?");
    $stmt->bind_param("ds", $commentId, $user);
    $stmt->execute();
    $stmt->close();
  }
}

$stmt2 = $mysqli->prepare("select comments.commentId, comments.comment, users.username, users.userId from comments, users where users.userId = comments.commenter and comments.storyId=? order by comments.commentId");
$stmt2->bind_param("d", $storyId);
$stmt2->execute();
$stmt2->bind_result($commentId, $comment, $commenter, $commenterId);
?>
<!DOCTYPE html>
<html>
  <?php include "./header.php" ?>
  <body>
    <?php echo "<h1>$title by $poster</h1>";
    echo " <a href=$url> $url </a>"; ?>
    <?php if ($user) { ?>
    <form action=<?php echo "./view.php?sid=$storyId";?> method="POST">
      <textarea rows="4" cols="50" name="comment"></textarea>
      <input type="hidden" name="action" value="post_comment">
      <input type="submit" value="Post Comment">
    </form>
    <?php } ?>
    <ul>
      <?php
      while($stmt2->fetch()) {
        echo "<li>$commenter says: $comment";
        if ($commenterId == $user) {
          echo "<form action=./view.php?sid=$storyId method=POST><input type=hidden name=comment_id value=$commentId><input type=hidden name=action value=delete_comment><input type=submit value=Delete></form>";
        }
        echo "</li>";
      }
      ?>
    </ul>
  </body>
</html>
