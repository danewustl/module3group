<?php
include "./guard.php";
include "./database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $action = $_POST['action'];
  $commentId = $_POST['cid'];
  $storyId = $_POST['sid'];
  $stmt = $mysqli->prepare('select comment from comments where commentId=?');
  $stmt->bind_param('d', $commentId);
  $stmt->execute();
  $stmt->bind_result($text);
  $stmt->fetch();
  $stmt->close();

?>
<!DOCTYPE html>
<html>
  <?php include "./header.php"; ?>
  <body>
    <form action=<?php echo "./view.php?sid=$storyId";?> method="POST">
      <textarea rows="4" cols="50" name="comment"><?php echo $text; ?></textarea>
      <input type="hidden" name="action" value="edit_comment">
      <input type="hidden" name="cid" value=<?php echo $commentId ?>>
      <input type="submit" value="Edit Comments">
    </form>
  </body>
</html>
<?php 
}
?>
