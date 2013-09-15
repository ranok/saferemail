<?php
  require_once('lib/include.php');
  // Detect is the user is logged in, otherwise direct to login page
  if (!isset($_SESSION['user']))
  {
    header("Location: login.php");
    exit();
  }
  // Load the user object
  $user = unserialize($_SESSION['user']);
?>

<!DOCTYPE HTML>
<html>
<head>
  <script type="text/javascript">
    //  var publicKey = "<?php //print $user->pubkey; ?>";
    var name = "<?php print $user->name; ?>";
    var email = "<?php print $user->email; ?>";
  </script>
  <script type="text/javascript" src="jquery.min.js"></script>
  <script type="text/javascript" src="openpgpjs/resources/openpgp.js"></script>
  <script type="text/javascript" src="smail.js"></script>
  <script type="text/javascript">
    function smailOnLoad()
    {
      $(".enc").each(function(index) {
        $(this).text = rsaDecrypt(openpgp.keyring.exportPrivateKey, $(this).text());
      });
    }
  </script>
  <link rel="stylesheet" type="text/css" href="smail.css" media="screen" />
  <title>SecureMail</title>
</head>

<body onload="smailOnLoad()">
  <!-- body overlay for fancy transparent backgrounds and such -->
  <div id="body_overlay">
    <!-- Header/Logo -->
    <div id="header">
      <div id="logo">sMail</div>
      <div class="top_nav" onClick="loadFileDiv('#content', 'compose.php')">Compose</div>
      <div class="top_nav" onClick="logOff()">Log Off</div>
      <div style="clear:both;"><br/></div>
    </div>

    <div id="container">

      <div id="sidebar">
        <ul>
          <li onClick="loadFileDiv('#content', 'mailbox.php')">
            Inbox
          </li>
        </ul>
      </div>

      <div id="content">
        <?php require_once("mailbox.php"); ?>
      </div>

    </div> <!-- body_overlay -->

    <div id="footer"></div>
    <div style="clear:both;"><br/></div>
  </div>
</body>

</html>
