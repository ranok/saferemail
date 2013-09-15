<?php
require_once('lib/include.php');

// Determine if creating new user or logging in
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

/**
 Function create_user

 Creates a new user
 */
function create_user()
{
  $u = new User();
  // Ensure there is not an existing user with the same email address
  if($u->loadByEmail($_POST['email']))
    return;
  $u->pubkey = $_POST['pubkey'];
  $u->name = $_POST['name'];
  $u->email = $_POST['email'];
  $u->encprivkey = $_POST['encprivkey'];

  $u->saveNew();

  // Reload to get user ID set
  $u->loadByEmail($_POST['email']);
  $_SESSION['user'] = serialize($u);
}

/**
 Function login

 Logs in a user
 */
function login()
{
  if ($_POST['nonce'] == $_SESSION['nonce'])
    {
      unset($_SESSION['nonce']);
      $u = new User();
      $u->loadByEmail($_SESSION['loginemail']);
      $_SESSION['user'] = serialize($u);
      unset($_SESSION['loginemail']);
    }
  else
    {
      // Wrong nonce
      unset($_SESSION['nonce']);
      header('Location: login.php?err=1');
      exit();
    }
}
?>