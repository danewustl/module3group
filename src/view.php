<?php
include "./guard.php";
include "./database.php";

$storyId=htmlspecialchars($_GET['sid']);

$stmt = $mysqli->prepare("update stories set hits = hits + 1 where storyId = ?");
$stmt->bind_param('d', $storyId);
$stmt->execute();
$stmt->close();
$stmt = $mysqli->prepare("select stories.title, users.username, stories.link, stories.hits from stories, users where users.userId = stories.poster and stories.storyId = ?");
$stmt->bind_param('d', $storyId);
$stmt->execute();
$stmt->bind_result($title, $poster, $url, $hits);
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
    $commentId = htmlspecialchars($_POST['comment_id']);
    $stmt = $mysqli->prepare("delete from comments where commentId=? and commenter=?");
    $stmt->bind_param("ds", $commentId, $user);
    $stmt->execute();
    $stmt->close();
  } elseif ($action == 'edit_comment') {
    $comment = htmlspecialchars($_POST['comment']);
    $commentId = $_POST['cid'];
    $stmt = $mysqli->prepare("update comments set comment = ?, edited = true where commentId = ?");
    $stmt->bind_param("sd", $comment, $commentId);
    $stmt->execute();
    $stmt->close();
  }
}

$stmt2 = $mysqli->prepare("select comments.commentId, comments.comment, users.username, users.userId, comments.edited from comments, users where users.userId = comments.commenter and comments.storyId=? order by comments.commentId");
$stmt2->bind_param("d", $storyId);
$stmt2->execute();
$stmt2->bind_result($commentId, $comment, $commenter, $commenterId, $edited);
?>
<!DOCTYPE html>
<html>
  <?php include "./header.php" ?>
  <body>
    <a href="./news.php">Return to home page.</a>
    <?php 
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
      echo "<h1 class=\"login\">$title A discussion forum by $poster</h1>";
      echo "<p>$hits page views.</p>";
      echo "<h4> This is a discussion forum, no valid URL has been associated with this entry. </h4>";
    }
    else{
      echo "<h1 class=\"login\">$title. Shared by $poster</h1>";
      echo "<p>$hits page views.</p>";
      echo "<p><a href=$url> $url </a></p>";
    }
    ?>
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
        if ($edited) {
          echo "(edited)";
        }
        if ($commenterId == $user) {
          echo "<form action=./view.php?sid=$storyId method=POST><input type=hidden name=comment_id value=$commentId><input type=hidden name=action value=delete_comment><input type=submit value=Delete></form>";
          echo "<form action=./edit.php method=POST><input type=hidden name=cid value=$commentId><input type=hidden name=sid value=$storyId><input type=hidden name=action value=edit_comment><input type=submit value=Edit></form>";
        }
        echo "</li>";
      }
      ?>
    </ul>
  </body>
</html>
