<?php
require('lib/include.php');
if (isset($_SESSION['user']))
  {
    header("Location: index.php");
    exit();
  }
?>
<!DOCTYPE HTML>
<html>
  <head>
    <script type="text/javascript">
      var emaildomain = "<?php print SMAIL_DOMAIN; ?>";
    </script>
    <script type="text/javascript" src="jquery.min.js"></script>
    <script type="text/javascript" src="openpgpjs/resources/openpgp.js"></script>
    
    <script type="text/javascript" src="smail.js"></script>
    <script type="text/javascript">
      function smailOnLoad()
      {

      }
    </script>
    
    <title>SecureMail Login/Sign Up</title>
  </head>
  <body onLoad="smailOnLoad()">
    
    <div id="login">
      Login with your username and password here:
    <form method="post" id="keyform" action="#" onSubmit="return false;">
      Email Address: <input type="text" name="email" value="" id="loginemail" /><br />
      Password: <input type="password" name="password" id="loginpassword" /><br />
      <button onClick="login()">Login to SMail!</button>
    </form>
    </div>
    <br />
    <p style="font-weight: bold;">Or</p>
    <div id="create">
      Create a new account:
    <form method="post" id="keyform" action="#" onSubmit="return false;">
      Name: <input type="text" name="name" value="" id="createname"/><br />
      Email Address: <input type="text" name="email" value="" id="createemail" onchange="validateSmailEmail()"/>@<?php print SMAIL_DOMAIN;?> <div id="createemailcheck"></div><br />
      Password: <input type="password" name="password" id="createpassword" /><br />
      <button onClick="createUser()">Make new SMail account!</button>
    </form>
    </div>
    
  </body>
  
</html>
