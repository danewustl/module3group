<?php
include "./guard.php";
include "./database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $action = $_POST['action'];
  $storyId = $_POST['sid'];
  $stmt = $mysqli->prepare('select link from stories where storyId=?');
  $stmt->bind_param('d', $storyId);
  $stmt->execute();
  $stmt->bind_result($text);
  $stmt->fetch();
  $stmt->close();

?>
<!DOCTYPE html>
<html>
  <?php include "./header.php"; ?>
  <body>
    <form action=<?php echo "./news.php?sid=$storyId";?> method="POST">
      <textarea rows="4" cols="50" name="link"><?php echo $text; ?></textarea>
      <input type="hidden" name="action" value="edit_story">
      <input type="submit" value="Edit Story">
    </form>
  </body>
</html>
<?php 
}
?>
