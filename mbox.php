<?php
require_once('lib/include.php');
$exploded_email = explode('@', urldecode($_POST['to']));
$domain = $exploded_email[1];
$username = $exploded_email[0];

if ($domain == SMAIL_DOMAIN)
{
  $u = UserQuery::create()->findOneByEmail($_POST['to']);
  if ($u != NULL)
  {
    $msg = new Message();
    $msg->setMessage($_POST['message']);
    $msg->setFrom($_POST['from']);
    $msg->setSubject($_POST['subject']);
    $msg->setUserId($u->getId());
    $msg->save();
  }
}
?>