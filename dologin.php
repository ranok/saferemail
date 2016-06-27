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
  if(UserQuery::create()->findOneByEmail($_POST['email']) != NULL)
    return;
  $u->setPubkey($_POST['pubkey']);
  $u->setName($_POST['name']);
  $u->setEmail($_POST['email']);
  $u->setEncprivkey($_POST['encprivkey']);

  $u->save();

  // Reload to get user ID set
  $u = UserQuery::create()->findOneByEmail($_POST['email']);
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
      $u = UserQuery::create()->findOneByEmail($_SESSION['loginemail']);
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