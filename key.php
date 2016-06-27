<?php
require_once('lib/include.php');

if (isset($_GET['email']))
  {
    $u = UserQuery::create()->findOneByEmail($_GET['email']);
    if ($u != NULL)
      print $u->getPubkey();
  }
?>