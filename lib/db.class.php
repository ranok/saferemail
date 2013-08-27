<?php
  /*
   Class DB
   Author: Jacob Torrey
   Date: 3/26/09
   Description: Provides an easy way to interact with the database backend
   */

class DB {
  
  private $conn;
  private $res;
  
  function __construct() {
    require('db.config.php');
    $this->conn = mysql_connect($host, $username, $password);
    $this->res = -1;
    @mysql_select_db($database, $this->conn);
  }

  function __destruct() {
    @mysql_close($this->conn);
  }

  function query($q) {
    $this->res = mysql_query($q, $this->conn) or die(mysql_error());
  }

  function get_row() {
    if($this->res == -1)
      return false;
    return mysql_fetch_array($this->res);
  }

  function num_rows() {
    if($this->res == -1)
      return -1;
    return mysql_num_rows($this->res);
  }

  function sanitize($string) {
    return (get_magic_quotes_gpc()) ? $string : addslashes($string);
  }

  }
?>