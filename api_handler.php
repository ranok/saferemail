<?php
require_once('lib/include.php');

switch ($_GET['method'])
  {
  case "get_public_key":
    get_public_key($_GET['email']);
    break;
  case "user_exists":
    user_exists($_GET['email']);
    break;
  case "logoff":
    unset($_SESSION['user']);
    break;
  case "get_message":
    get_message($_GET['id']);
    break;
  case "send":
    send_message($_POST['email'], $_POST['subject'], $_POST['message'], $_POST['post']);
    break;
  default:
    break;
  }

function get_message($id)
{
  $db = new DB();
  $id = $db->sanitize($id);
  $db->query("SELECT (`message`) FROM `message` WHERE `id` = '$id' LIMIT 1");
  if ($db->num_rows() == 1)
    {
      $row = $db->get_row();
      print $row['message'];
    }
}

function send_message($email, $subject, $message, $post)
{
  if ($post)
    {
      $exploded_email = explode('@', $email);
      $domain = $exploded_email[1];
      $username = $exploded_email[0];
      
      if ($domain == SMAIL_DOMAIN)
	{
	  $fu = unserialize($_SESSION['user']);
	  $u = new User();
	  $u->loadByEmail($email);
	  $db = new DB();
	  $message = $db->sanitize($message);
	  $subject = $db->sanitize($subject);
	  $db->query("INSERT INTO `message` (`user`, `from`, `subject`, `message`) VALUES ('{$u->id}', '{$fu->email}', '$subject', '$message');");
	}
      else
	{
	  $records = dns_get_record($domain, DNS_TXT);
	  for ($i = 0; $i < count($records); $i++)
	    {
	      foreach ($records[$i]['entries'] as $record)
		{
		  if (preg_match('/^v=shmp1 http/', $record))
		    {
		      // FIXME Fix this to POST to this address
		      // Use that address to get public key
		      print file_get_contents(preg_replace('/^v=shmk1 /', '', $record).'?email='.$email);
		    }
		}
	    }
	} 
    }
  else
    {
      mail($email, 'Subject: '.$subject, $message);
    }
}

function user_exists($email)
{
  $u = new User();
  if($u->loadByEmail($email))
    print "true";
  else
    print "false";
}

function get_public_key($email)
{
  $exploded_email = explode('@', $email);
  $domain = $exploded_email[1];
  $username = $exploded_email[0];

  if ($domain == SMAIL_DOMAIN)
    {
      $u = new User();
      $u->loadByEmail($email);
      print $u->pubkey;
    }
  else
    {
      $records = dns_get_record($domain, DNS_TXT);
      for ($i = 0; $i < count($records); $i++)
	{
	  foreach ($records[$i]['entries'] as $record)
	    {
	      if (preg_match('/^v=shmk1 http/', $record))
		{
		  // Use that address to get public key
		  print file_get_contents(preg_replace('/^v=shmk1 /', '', $record).'?email='.$email);
		}
	    }
	}
    }
}

?>