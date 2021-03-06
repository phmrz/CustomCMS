<?php
require_once('require/backend.php');
require('require/credentials.php');
$db = new DatabaseConnection();

if(!$db->auth()) {
  header('Location: index');
}

if (isset($_GET['id'])) {
  $GETid = $db->escape_string($_GET['id']); //sanitize GET request
  if (isset($_GET['rem'])) {
    $db->removeSavedPost($GETid);
  } else {
    $db->addToSavedPosts($GETid);
  }
}
?>

<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <script>applyStyle();</script>
  <link rel="stylesheet" href="style/index.css">
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php'; ?>
  <div id='content'>
    <?php
    $userid = $db->getCurUser();
    if ($getSaved = $db->query("SELECT POSTID FROM saved WHERE USERID = $userid")) {
      if ($getSaved->num_rows > 0) {
        echo $db->postsAusgeben('DESC');
      } else {
        echo 'You haven\'t saved any posts yet';
      }
    } else {
      echo 'query error';
    }
    ?>
  </div>
</div>

<script>
  applyStyle();
  updateMD();
</script>
</body>
</html>
