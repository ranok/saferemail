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
      var keysize = <?php print SMAIL_KEYSIZE; ?>;
    </script>
    <script type="text/javascript" src="jquery.min.js"></script>
    <script type="text/javascript" src="openpgpjs/resources/openpgp.js"></script>
    
    <script type="text/javascript" src="smail.js"></script>
    <script type="text/javascript">
      function smailOnLoad()
      {

      }
    </script>
    <link href="smail.css" rel="stylesheet" type="text/css">

    <title>SecureMail Login/Sign Up</title>
  </head>
  <body onLoad="smailOnLoad()">
    <div id="login_container">
      <h1>sMail</h1>
      <div id="login">
        <h3>Sign In</h3>
        <form method="post" action="#" onSubmit="return false;">
          
          <div class="field_label">Username/Email</div>
          <input type="text" name="email" value="" id="loginemail" /><br />
        
          <div class="field_label">Password</div>
          <input type="password" name="password" id="loginpassword" /><br />
        
          <div class="field_label">
          
            <button onClick="login()">Login to SMail!</button>
          
            <div style="float:right">
              <a href="javascript:hideShowByID('login', 'create');">Create an account</a>
            </div>
          
          </div>
        </form>
      </div>
      <div id="create" style="display:none">
        <h3>Create an account</h3>
        <form method="post" action="#" onSubmit="return false;">
          
          <div class="field_label">Name</div>
          <input type="text" name="name" value="" id="createname"/><br />
      
          <div class="field_label">Username</div>
          <input type="text" name="email" value="" id="createemail" onchange="validateSmailEmail()"/>
          
          @<?php print SMAIL_DOMAIN;?> 
          <div id="createemailcheck"></div><br />
      
          <div class="field_label">Password</div>
          <input type="password" name="password" id="createpassword" /><br />
          
          <button onClick="createUser()">Make new SMail account!</button>

        </form>
      </div>
    </div>    
  </body>
  
</html>
