<?php if (!isset($_COOKIE["logcheck"])):
  header('Location: index');

elseif (isset($_POST["title"])):
  require 'require/credentials.php';
  $mysqli = new mysqli("$servername", "$username", "$password", "$dbname");

  if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
  }

  $username = $_COOKIE["uname"];
  $mysqliUserID = $mysqli->query("SELECT USERID FROM $usertable WHERE USERNAME = '$username'");
  if (!$mysqliUserID) {
      echo $mysqli->error;
  }

  $row_uid = $mysqliUserID->fetch_assoc();
  $userID= $row_uid["USERID"];

  $title = $mysqli->escape_string($_POST["title"]);
  $content = $mysqli->escape_string($_POST["content"]);

  $insert = $mysqli->query("INSERT INTO $posttable (USERID, TITLE, CONTENT) VALUES ('$userID', '$title', '$content')");
  if (!$insert) {
      echo $mysqli->error;
  }

  $mysqli->close();
  header("Location: index");

endif; ?>

<!doctype html>
<html>
<head>
  <?php require 'require/head.php';?>
  <link rel="stylesheet" href="style/myposts.css" id="pagestyle">
</head>
<body>

<div id='grid-wrap'>
  <?php require 'require/header.php';?>
  <?php require 'require/sidebar.php'; ?>
  <div id='content'>

    <form action="" method="POST">
      <p>New Post</p>

      <p id="titlecharswrapper">0/200</p>
      <input id="titleField" name="title" type="text" placeholder="Title" maxlength="200" oninput="updateCharsLeft(200, 'title')" required/>

      <p id="contentcharswrapper">0/10000</p>
      <textarea id="contentArea" name="content" placeholder="Post content" rows="20" cols="100" spellcheck="false" maxlength="10000" oninput="updateCharsLeft(10000, 'content')" required></textarea>

      <button type="submit" id="btnpost" class="primary-btn">Post</button>
    </form>
  </div>
</div>

<script>applyStyle();</script>

</body>
</html>
