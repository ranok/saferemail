<?php
require_once('lib/include.php');
// Detect is the user is logged in, otherwise direct to login page
if (!isset($_SESSION['user']))
{
  header("Location: login.php");
  exit();
}
?>
<div id="compose">
    <form method="post" id="composeform" action="javascript: return(false);">
    	<input type="text" id="toemail" name="toemail" value="To" onchange="determineEncStatus();" onclick="if(this.value == 'To') this.value = '';" />
    	
    	<div id="emailstatus"></div>
    	
    	<input type="text" name="subject" id="subject" value="Subject" onclick="if(this.value == 'Subject') this.value = '';"/>
    	<br />
        <textarea name="message" id="message" rows="7" cols="25" tabindex=""></textarea>
        <br />
        <button onClick="sendMessage()">Send</button>
    </form>
    <div id="send_complete" style="display:none; padding: 10px;">
        Message sent!
    </div>
</div>