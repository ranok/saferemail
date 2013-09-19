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

      // If an invalid URL is provided load the inbox
      if (load_from_url_hash(0) == 1) {
        history.replaceState({view: "inbox"}, document.title, "#inbox");
        $("#content").load("mailbox.php");
      }
    }

    // This gets called if a user requests a url directly 
    // (as opposed to clicking ajax links)
    // or if a user uses the forward and back buttons
    function load_from_url_hash(pop)
    {
      var hash = window.location.hash;
      var state = hash.split('/');
      var url = state[0];

      switch (state[0]) {
        case "#inbox":
          if (state[1] != null) {
            $("#content").load("message.php");
            showMessage(state[1]);
            url += '/' . state[1];
          } else {
            $("#content").load("mailbox.php");
          }
          break;
        case "#compose":
          $("#content").load("compose.php");
          break;
        default:
          return 1;
          break;
      }
      // Requesting a url directly for the first time
      if (!history.state) {
        history.replaceState({view: url}, "sMail", url);
      }
      // Requesting a url directly after having established state
      else if (pop == 0) {
        // Don't save state if they are refreshing the page
        if (history.state.view != url)
          history.pushState({view: url}, "sMail", url);
      }
      return 0;
    }

    window.onpopstate = (function(event) {
      load_from_url_hash(1);
    });

  </script>
  <link rel="stylesheet" type="text/css" href="smail.css" media="screen" />
  <title>SecureMail</title>
</head>

<body onload="smailOnLoad()">

  <div id="body_overlay">
    <!-- Header/Logo -->
    <div id="header">
      <div id="logo">sMail</div>
      <div class="button" id="compose_button" onClick="goto_compose()">Compose</div>
      <div class="button" onClick="load_from_url_hash()">Refresh</div>
      <!-- <div class="button drop_button" onClick="">
          Select
          <image src="images/droparrow.png" />
      </div> -->
      <div class="button" onClick="delete_selected_mail()">Delete</div>
      <div class="button" id="logoff_button" onClick="logOff()">Log Off</div>
      <div style="clear:both;"></div>
    </div>

    <div id="container">

      <div id="sidebar">
        
        <ul>
          <li onClick="goto_messages('#inbox')">
            Inbox
          </li>
          <li>
            Sent Mail
          </li>
        </ul>
      </div> <!-- /sidebar -->

      <div id="content">
        <!-- Content loaded via javascript in smailOnLoad -->
      </div>

    </div> <!-- /body_overlay -->

    <div id="footer"></div>
    <div style="clear:both;"><br/></div>
  </div>
</body>

</html>
