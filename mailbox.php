<?php
require_once('lib/include.php');
// Detect is the user is logged in, otherwise direct to login page
if (!isset($_SESSION['user']))
{
  header("Location: login.php");
  exit();
}

// Load the user object
$user = unserialize($_SESSION['user']);

/**
 Function: getMessages
 @param $userid ID of user to grab latest messages
 @param $num Number of messages to return (max)

 @return Assoc. array of at most $num of $userid's most recent messages 
*/
 function getMessages($userid, $num)
 {
  $db = new DB();
  $db->query("SELECT * FROM `message` WHERE `user` = '$userid' ORDER BY `timestamp` DESC LIMIT $num;");
  $out = array();
  for ($i = 0; $i < $db->num_rows(); $i++)
  {
    $out[] = $db->get_row();
  }
  return $out;
}

$messages = getMessages($user->id, 15);
?>

<?php
if (count($messages))
{
  ?>
  <ul class="mailbox">

    <?php
             // Show inbox
    foreach ($messages as $message)
    {
      ?>

      <li>
        <span class="mailbox_controls">
          <input type="checkbox" />
        </span>

        <span class="mailbox_from" onclick="goto_message(<?php print $message['id']; ?>)">
          <?php print preg_replace('/ <.*>/', '', $message['from']); ?>
        </span>

        <span class="mailbox_subject">
          <?php print $message['subject']; ?>
        </span>

        <span class="mailbox_preview">
          - this is a message preview
        </span>

        <span class="mailbox_date">
          <?php print $message['timestamp']; ?>
        </span>

      </li>

      <?php
    }
    ?>

  </ul>
  <?php
}
else
{
  ?>
  <p>No messages to display!</p>
  <?php
}
?>
