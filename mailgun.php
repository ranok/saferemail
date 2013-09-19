<?php
require_once('lib/include.php');
// For encrypting message
require_once('openpgpphp/lib/openpgp.php');
require_once('openpgpphp/lib/openpgp_crypt_rsa.php');
require_once('openpgpphp/lib/openpgp_crypt_aes_tripledes.php');

function strip_name($email)
{
  return preg_replace('/^.* </', '', preg_replace('/>$/', '', $email));
}

$exploded_email = explode('@', strip_name($_POST['To']));
$domain = $exploded_email[1];
$username = $exploded_email[0];

if ($domain == SMAIL_DOMAIN)
{
  $u = new User();
  $u->loadByEmail(strip_name($_POST['To']));
  if ($u->id != -1)
  {
    $db = new DB();
    $message = $db->sanitize($_POST['body-plain']);
    $from = $db->sanitize($_POST['From']);
    $subject = $db->sanitize($_POST['Subject']);

    if (!preg_match('/-----BEGIN PGP MESSAGE-----/', $message))
      {
	$key = OpenPGP_Message::parse(OpenPGP::unarmor($u->pubkey));
	$data = new OpenPGP_LiteralDataPacket($message, array('format' => 'u'));
	$enc = OpenPGP_Crypt_AES_TripleDES::encrypt($key, new OpenPGP_Message(array($data)));
	$message = OpenPGP::enarmor($enc->to_bytes(), "PGP MESSAGE");
      }
    
    $db->query("INSERT INTO `message` (`user`, `from`, `subject`, `message`) VALUES ('{$u->id}', '$from', '$subject', '$message');");
  }
}
?>