<?php
require_once('lib/include.php');
$exploded_email = explode('@', urldecode($_POST['to']));
$domain = $exploded_email[1];
$username = $exploded_email[0];

if ($domain == SMAIL_DOMAIN)
{
  $u = new User();
  $u->loadByEmail(urldecode($_POST['to']));
  if ($u->id != -1)
  {
    $db = new DB();
    $message = $db->sanitize($_POST['message']);
    $from = $db->sanitize(urldecode($_POST['from']));
    $subject = $db->sanitize(urldecode($_POST['subject']));

    $db->query("INSERT INTO `message` (`user`, `from`, `subject`, `message`) VALUES ('{$u->id}', '$from', '$subject', '$message');");
  }
}
?>