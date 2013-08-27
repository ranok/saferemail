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

/**
 Function: getMessages
 @param $userid ID of user to grab latest messages
 @param $num Number of messages to return (max)

 @return Assoc. array of at most $num of $userid's most recent messages 
*/
function getMessages($userid, $num)
{
  $db = new DB();
  $db->query("SELECT * FROM `message` WHERE `user` = '$userid' ORDER BY `timestamp` DESC LIMIT $num;");
  $out = array();
  for ($i = 0; $i < $db->num_rows(); $i++)
    {
      $out[] = $db->get_row();
    }
  return $out;
}

$messages = getMessages($user->id, 15);
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
    <div id="messages">
      <h1 style="text-align: center;">SecureMail Inbox</h1>
      <?php
	 if (count($messages))
	 {
	 ?>
      <h3>Message List:</h3>
      <ol id="ml">
	<?php
	 // Show inbox
	   foreach ($messages as $message)
	   {
	   ?>
	<li onclick="showMessage(<?php print $message['id']; ?>)"><div><?php print $message['from']; ?></div><div><?php print $message['timestamp']; ?></div><div class=""><?php print $message['subject']; ?></div></li>
	<?php
	   }
	   ?>
      </ol>
      <?php
	 }
	 else
	 {
	 ?>
      <p>No messages to display!</p>
      <?php
	 }
	 ?>
    </div>
    <div id="messagebox">
      
    </div>
    <button onClick="logOff()">Log Off</button>
    <br />
    <div id="compose">
      <p>Compose New Message:</p>
      <form method="post" id="composeform" action="javascript: false;">
	To: <input type="text" id="toemail" name="toemail" value="" onchange="determineEncStatus();" /><div id="emailstatus"></div><br />
	Subject: <input type="text" name="subject" id="subject" value="" /><br />
	<textarea name="message" id="message" rows="7" cols="25" tabindex=""></textarea><br />
	<button onClick="sendMessage()">Send Message</button>
      </form>
    </div>
  </body>
  
</html>
