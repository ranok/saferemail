<?php
require_once('lib/include.php');
print_r($_POST);

switch ($_POST['method'])
  {
  case "create_user":
    create_user();
    break;
  case "login":
    login();
    break;
  default:
    break;
  }
header('Location: index.php');
exit();

function create_user()
{
  // Add error checking
  $u = new User();
  $u->pubkey = $_POST['pubkey'];
  $u->name = $_POST['name'];
  $u->email = $_POST['email'];
  $u->encprivkey = $_POST['encprivkey'];

  $u->saveNew();
  
  $u->loadByEmail($_POST['email']);
  $_SESSION['user'] = serialize($u);
}

function login()
{
  if ($_POST['nonce'] == $_SESSION['nonce'])
    {
      unset($_SESSION['nonce']);
      $u = new User();
      $u->lookupByEmail($_SESSION['loginemail']);
      $_SESSION['user'] = $u;
      unset($_SESSION['loginemail']);
    }
  else
    {
      header('Location: login.php?err=1');
      exit();
    }
}
?>