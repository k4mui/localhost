<?php

require_once(__DIR__."/../models/board.php");

class DataAccess
{
  private $conn;
  private $users_txt_file;
  private $boards_xml_file;
  private $discussions_xml_file;

  function __construct()
  {
    $server = "localhost";
    $un = "root";
    $pwd = "555137";
    $db = "wheel_3";
    try {
      $this->conn = new PDO("mysql:host=$server;dbname=$db", $un, $pwd);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
      $this->conn = null;
      echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
    $this->users_txt_file = "/home/mutter101/development/massc/files/users.txt";
    $this->boards_xml_file = "/home/mutter101/development/massc/files/boards.xml";
    $this->discussions_xml_file = "/home/mutter101/development/massc/files/discussions.xml";
  }
  function __destruct() {
    $this->conn = NULL;
  }
  public function get_board_mysql($board_id) {
    $stmt = $conn->prepare("SELECT * FROM boards WHERE id = :id");
    $stmt->bindParam(':id', $board_id);
    $stmt->execute();
    $row = $stmt->fetch();
    var_dump($row);
  }
  public function insert_user($email_address, $password) {
    if (!$this->user_exists($email_address)) {
      $f = fopen($this->users_txt_file, "w") or die("Cannot open file");
      $hash = md5($password);
      fwrite($f, "$email_address $hash\n");
      fclose($f);
      return True;
    } else {
      return False;
    }
  }
  public function get_user($email_address) {
    $user = NULL;
    $f = fopen($this->users_txt_file, "r") or die("Cannot open file");
    $users = array();
    while($line = fgets($f)) {
      $parts = explode(" ", trim($line));
      $users[$parts[0]] = $parts[1];
    }
    fclose($f);
    foreach ($users as $e => $p) {
      if ($e === $email_address) {
        $user = new User;
        $user->set_email_address($e);
        $user->set_password_hash($p);
        $user->set_role(1);
        return $user;
      }
    }
    return $user;
  }
  public function user_exists($email_address) {
    $f = fopen($this->users_txt_file, "r") or die("Cannot open file");
    $users = array();
    while($line = fgets($f)) {
      $parts = explode(" ", trim($line));
      $users[$parts[0]] = $parts[1];
    }
    fclose($f);
    foreach ($users as $e => $p) {
      if ($e === $email_address) {
        return True;
      }
    }
    return False;
  }
  public function load_boards_to_array(& $data) {
    $xml = simplexml_load_file($this->boards_xml_file) or die("Cannot load xml");
    foreach ($xml->board as $b) {
      $data[(int)$b->id] = array(
        "title" => (string)$b->title,
        "icon" => (string)$b->fa_icon,
        "discussion_count" => 0,
        "post_count" => 0,
        "image_count" => 0
      );
    }
  }
  public function get_board($board_id) {
    $board = new board;
    $xml = simplexml_load_file($this->boards_xml_file) or die("Cannot load xml");
    foreach ($xml->board as $b) {
      if ((int)$b->id === $board_id) {
        $board->set_id((int)$b->id);
        $board->set_title((string)$b->title);
        $board->set_icon((string)$b->fa_icon);
        $board->set_locked((int)$b->locked);
      }
    }
    return $board;
  }
}


?>
