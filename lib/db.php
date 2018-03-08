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
    $db = "wheel_test";
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
    $stmt = $this->conn->prepare("SELECT * FROM boards WHERE id = :id");
    $stmt->bindParam(':id', $board_id);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row ? board::with_row($row) : NULL;
  }
  public function insert_discussion($title, $full_text, $image_id, $user_id, $board_id) {
    try {
      $stmt = $this->conn->prepare("INSERT INTO discussions(title, full_text, author_id, image_id, board_id) VALUES(:title, :full, :author, :img, :board)");
      $stmt->bindParam(":title", $title);
      $stmt->bindParam(":full", $full_text);
      $stmt->bindParam(":author", $user_id);
      $stmt->bindParam(":img", $image_id);
      $stmt->bindParam(":board", $board_id);
      $stmt->execute();
      return True;
    } catch(Exception $e) {
      print_r($e);
      return False;
    }
  }
  public function insert_image($image, $user_id) {
    $image_dir = "/home/mutter101/development/massc/images/usercontents";
    $target_filename = uniqid("$user_id-", True);
    $info = getimagesize($image["tmp_name"]);
    $extension = image_type_to_extension($info[2]);
    do {
      $target_filename = uniqid("$user_id-", True);
    } while(file_exists("$image_dir/$target_filename$extension"));

    if (move_uploaded_file($image["tmp_name"], "$image_dir/$target_filename$extension")) {
      try {
        $stmt = $this->conn->prepare("INSERT INTO images(filename, addition_timestamp, size) VALUES (:fn, :addition, :sz)");
        $stmt->bindParam(":fn", $fn);
        $stmt->bindParam(":addition", $addition);
        $stmt->bindParam(":sz", $sz);
        $fn = "$target_filename$extension";
        $addition = date("Y-m-d H:i:s", filectime("$image_dir/$target_filename$extension"));
        $sz = filesize("$image_dir/$target_filename$extension");
        $stmt->execute();
        return $this->conn->lastInsertId();
      } catch(Exception $exception) {
        print_r($exception);
        unlink("$image_dir/$target_filename$extension"); 
        return False; 
      }
    } else {
      return False;
    }
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
