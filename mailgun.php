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
  $u = UserQuery::create()->findOneByEmail(strip_name($_POST['to']));
  if ($u !== NULL)
  {
    if (!preg_match('/-----BEGIN PGP MESSAGE-----/', $message))
      {
	$key = OpenPGP_Message::parse(OpenPGP::unarmor($u->getPubkey));
	$data = new OpenPGP_LiteralDataPacket($message, array('format' => 'u'));
	$enc = OpenPGP_Crypt_AES_TripleDES::encrypt($key, new OpenPGP_Message(array($data)));
	$message = OpenPGP::enarmor($enc->to_bytes(), "PGP MESSAGE");
      }

    $msg = new Message();
    $msg->setSender($_POST['From']);
    $msg->setUserId($u->getId());
    $msg->setSubject($_POST['Subject']);
    $msg->setMBody($message);

    $msg->save();
  }
}
?>