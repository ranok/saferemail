<?php
require_once('lib/include.php');
// Detect is the user is logged in, otherwise direct to login page
if (!isset($_SESSION['user']))
{
  header("Location: login.php");
  exit();
}
?>

<div id="messagebox">
 <div id="message_subject"></div>
 <div id="message_date"></div>
 <div id="message_from"></div>
 <div id="message_content">Nothing to display.</div>
</div>
