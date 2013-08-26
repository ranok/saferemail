<?php
require_once('lib/include.php');

if (isset($_GET['email']))
  {
    $u = new User();
    $u->loadByEmail($_GET['email']);
    if ($u->id != -1)
      print $u->pubkey;
  }
?>