<?php
  function isLoggedIn(){
    return (isset($_COOKIE['logcheck'])) ? true : false;
  }

  function invertSortOrder() {
    return (isset($_GET['sort']) and (!$_GET['sort'] or $_GET['sort'] == 'DESC')) ? "ASC" : "DESC";
  }

  function getSortSVG() {
    return (isset($_GET['sort']) and (!$_GET['sort'] or $_GET['sort'] == 'DESC')) ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M3 18h6v-2H3v2zM3 6v2h18V6H3zm0 7h12v-2H3v2z"/></svg>' : '<svg rotate="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M3 18h6v-2H3v2zM3 6v2h18V6H3zm0 7h12v-2H3v2z"/></svg>';
  }

  function randomString($length) {
      $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!$.";
      $c_length = strlen($characters);
      $string = "";
      for ($i = 0; $i < $length; $i++) {
          $string .= $characters[rand(0, $c_length - 1)];
      }
      return $string;
  }

  class DatabaseConnection extends mysqli {
    function __construct() {
      require('credentials.php');
      @parent::__construct("$servername", "$username", "$password", "$dbname");

      if ($this->connect_error) {
        die($this->connect_errno . $mysqli->connect_error);
      }
    }

    function postsAusgeben($order) {
      require('credentials.php');

      $order = ($order == 'ASC') ? 'DESC' : 'ASC';

      $userID = self::getUserID();
      if (basename($_SERVER['PHP_SELF']) == "myposts.php")
        $sqlQuery = "SELECT POSTID, substring(TITLE, 1, 50) AS TITLE, substring(CONTENT, 1, 200) AS CONTENT, DATE, substring(DATE, 1, 10) AS DAY, substring(DATE, 11, 19) AS TIME, USERNAME from $posttable, $usertable WHERE $posttable.USERID = $usertable.USERID AND $posttable.USERID = $userID ORDER BY DATE $order";

      else
        $sqlQuery = "SELECT POSTID, substring(TITLE, 1, 50) AS TITLE, substring(CONTENT, 1, 200) AS CONTENT, DATE, substring(DATE, 1, 10) AS DAY, substring(DATE, 11, 19) AS TIME, USERNAME from $posttable, $usertable WHERE $posttable.USERID = $usertable.USERID ORDER BY DATE $order";

      $r = @parent::query($sqlQuery);

      $return = "";
      while ($row = $r->fetch_assoc()){
        $return .= <<<MYSQL
        <a href='onepost.php?id=$row[POSTID]'>
          <div class='post'>
            <img class='thumbnail' src='assets/dummy-thumbnail.png'>

            <div class='post-without-tn'>
              <div class='post-info'>
                <p class='title'>$row[TITLE]</p>
                <div class='date-uname'>
                  <p class='username'>$row[USERNAME]</p>
                  <p class='at'>on</p>
                  <p class='date'>$row[DAY] at $row[TIME]</p>
                </div>
              </div>
              <span class='post-text md'>$row[CONTENT]...</span>
            </div>
          </div>
        </a>
MYSQL;
      }
      return $return;
    }

    function einenPostAusgeben() {
      require('credentials.php');

      $r = @parent::query("SELECT TITLE, CONTENT, substring(DATE, 1, 10) AS DAY, substring(DATE, 11, 19) AS TIME, USERNAME from $posttable, $usertable WHERE $posttable.USERID = $usertable.USERID AND POSTID = $_GET[id]");

      $row = $r->fetch_assoc();
      return <<<RETURN
      <div id='post'>
        <p id='title'>$row[TITLE]</p>
        <div id='post-info'>
          <p id='username-top'>
            <span>posted by</span> $row[USERNAME]
          </p>
          <p id='date'>
            <span>on</span> $row[DAY] <span>at</span> $row[TIME]
          </p>
        </div>
        <span id='post-text' class='md'>$row[CONTENT]</span>
      </div>
RETURN;
    }

    function getUserRole() {
      return @parent::query("SELECT ROLE FROM user WHERE USERNAME = '$_COOKIE[uname]'")->fetch_assoc()[ROLE];
    }

    function deletePost() {
      @parent::query("DELETE FROM post WHERE POSTID = $_GET[id]");
      header('Location: index');
    }

    function getUserID() {
      $getUserID = @parent::query("SELECT USERID FROM user WHERE USERNAME = '$_COOKIE[uname]'");
      $row = $getUserID->fetch_assoc();
      return $row["USERID"];
    }
  }
?>
