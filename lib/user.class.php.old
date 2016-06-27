<?php
require_once('db.class.php');

class User
{
  public $id, $name, $email, $encprivkey, $pubkey;

  function __construct()
  {
    $this->id = -1;
    $this->name = '';
    $this->email = '';
    $this->encprivkey = '';
    $this->pubkey = '';
  }
  
  function loadByEmail($email)
  {
    $db = new DB();
    $email = $db->sanitize($email);
    $db->query("SELECT * FROM `user` WHERE `email` = '$email' LIMIT 1;");
    if ($db->num_rows() == 1)
      {
	$row = $db->get_row();
	$this->id = $row['id'];
	$this->name = $row['name'];
	$this->email = $row['email'];
	$this->encprivkey = $row['encprivkey'];
	$this->pubkey = $row['pubkey'];
	return true;
      }
    return false;
  }

  function saveNew()
  {
    $db = new DB();
    $db->query("SELECT id FROM `user` WHERE `email` = '{$this->email}' LIMIT 1;");
    if ($db->num_rows() == 1) // User already exists
      return false;
    $db->query("INSERT INTO `user` (`name`, `email`, `pubkey`, `encprivkey`) VALUES ('{$this->name}', '{$this->email}', '{$this->pubkey}', '{$this->encprivkey}');");
    return true;
  }
  
}
?>