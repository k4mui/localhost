<?php
require_once(__DIR__."/../models/board.php");
require_once(__DIR__."/../models/discussion.php");
require_once(__DIR__."/../models/user.php");


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
      $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
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
  
  public function insert_reply($full_text, $image_id, $user_id, $discussion_id) {
    try {
      $stmt = $this->conn->prepare("INSERT INTO replies(full_text, author_id, image_id, discussion_id) VALUES(:full, :author, :img, :dis)");
      $stmt->bindParam(":full", $full_text);
      $stmt->bindParam(":author", $user_id);
      $stmt->bindParam(":img", $image_id);
      $stmt->bindParam(":dis", $discussion_id);
      $stmt->execute();
      return True;
    } catch(Exception $e) {
      print_r($e);
      return False;
    }
  }
  public function insert_reply_l($full_text, $user_id, $discussion_id) {
    try {
      $stmt = $this->conn->prepare("INSERT INTO replies(full_text, author_id, discussion_id) VALUES(:full, :author, :dis)");
      $stmt->bindParam(":full", $full_text);
      $stmt->bindParam(":author", $user_id);
      $stmt->bindParam(":dis", $discussion_id);
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
  public function insert_user_mysql($email_address, $password) {
    try {
      $stmt = $this->conn->prepare("INSERT INTO users(email_address, password_hash) VALUES(:email, :pwd)");
      $stmt->bindParam(":email", $email_address);
      $stmt->bindParam(":pwd", $hash);
      $hash = md5($password);
      $stmt->execute();
      return True;
    } catch(Exception $e) {
      print_r($e);
      return False;
    }
  }
  public function get_board_mysql($board_id) {
    $stmt = $this->conn->prepare("SELECT * FROM boards WHERE id = :id");
    $stmt->bindParam(':id', $board_id);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row ? board::with_row($row) : NULL;
  }
  public function get_user_mysql($email_address) {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE email_address = :email");
    $stmt->bindParam(':email', $email_address);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row ? user::with_row($row) : NULL;
  }
  public function get_account_info($user_id) {
    $sql = "SELECT (SELECT COUNT(*) FROM replies WHERE author_id = :uid) as reply_count,
                   (SELECT COUNT(*) FROM discussions WHERE author_id = :uid) as discussion_count,
                   (SELECT COUNT(*) FROM images, replies WHERE replies.author_id = :uid AND replies.image_id = images.id) as image_count_r,
                   (SELECT COUNT(*) FROM discussions, images WHERE discussions.author_id = :uid AND discussions.image_id = images.id) as image_count_d,
                   (SELECT registration_timestamp FROM users where id = :uid) as joined_on";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':uid', $user_id);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row;
  }
  public function get_discussion($discussion_id) {
    try {
      $stmt = $this->conn->prepare("SELECT * FROM discussions WHERE id = :id");
      $stmt->bindParam(':id', $discussion_id);
      $stmt->execute();
      $row = $stmt->fetch();
      return $row ? discussion::with_row($row) : NULL;
    } catch(Exception $e) {
      return NULL;
    }
  }
  public function get_discussion_x($discussion_id) {
    try {
      $stmt = $this->conn->prepare("SELECT d.author_id, d.archived, d.id, d.title, d.creation_timestamp, d.full_text, d.board_id, i.filename, b.fa_icon, b.title AS board_title  FROM discussions AS d, images AS i, boards AS b WHERE d.id = :id AND d.image_id = i.id AND b.id = d.board_id");
      $stmt->bindParam(':id', $discussion_id);
      $stmt->execute();
      $row = $stmt->fetch();
      return $row ? discussion::with_row_x($row) : NULL;
    } catch(Exception $e) {
      return NULL;
    }
  }
  public function get_board_x($board_id) {
    $sql = "SELECT b.id,
                   b.title,
                   b.fa_icon,
                   b.locked,
                   r.full_text,
                   (SELECT COUNT(*)
                    FROM discussions
                    WHERE board_id = b.id
                   ) AS discussion_count,
                   (SELECT COUNT(*)
                    FROM replies,
                         discussions
                    WHERE replies.discussion_id = discussions.id AND
                          discussions.board_id = b.id
                   ) AS reply_count,
                   (SELECT COUNT(*)
                    FROM images,
                         discussions
                    WHERE discussions.board_id = b.id AND
                          discussions.image_id = images.id
                    ) AS image_count_d,
                    (SELECT COUNT(*)
                    FROM images,
                         replies,
                         discussions
                    WHERE discussions.board_id = b.id AND
                          discussions.id = replies.discussion_id AND
                          replies.image_id = images.id
                    ) AS image_count_r,
                    (SELECT IFNULL(SUM(images.size), 0)
                    FROM images,
                         discussions
                    WHERE discussions.board_id = b.id AND
                          discussions.image_id = images.id
                    ) AS image_size_d,
                    (SELECT IFNULL(SUM(images.size), 0)
                    FROM images,
                         replies,
                         discussions
                    WHERE discussions.board_id = b.id AND
                          discussions.id = replies.discussion_id AND
                          replies.image_id = images.id
                    ) AS image_size_r
              FROM boards AS b,
                   rules AS r
              WHERE b.id = :id AND
                    b.id = r.board_id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $board_id);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row ? board::with_row_x($row) : NULL;
  }
  public function get_discussions($board_id) {
    try {
      $sql = "SELECT d.id,
                     d.title,
                     d.full_text,
                     d.creation_timestamp,
                     i.filename,
                     (SELECT COUNT(*)+1 FROM images, replies WHERE replies.discussion_id = d.id AND replies.image_id = images.id) AS image_count,
                     (SELECT COUNT(*) FROM replies WHERE replies.discussion_id = d.id) AS reply_count,
                     (SELECT creation_timestamp FROM replies WHERE replies.discussion_id = d.id ORDER BY id DESC LIMIT 1) AS last_reply_timestamp
              FROM discussions AS d,
                   images AS i
              WHERE d.board_id = :id AND
                    d.image_id = i.id AND
                    d.archived = 0";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $board_id);
      $stmt->execute();
      $rows = $stmt->fetchAll();
      //print_r($rows);
      return $rows;
    } catch(Exception $e) {
      return NULL;
    }
  }
  public function get_recent_discussions() {
    try {
      $sql = "SELECT d.id,
                     d.title,
                     d.creation_timestamp,
                     i.filename,
                     b.title AS board_title,
                     b.id AS board_id
              FROM boards AS b,
                   discussions AS d,
                   images AS i
              WHERE d.image_id = i.id AND
                    d.archived = 0 AND
                    b.id = d.board_id
              ORDER BY d.id DESC
              LIMIT 5";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll();
      //print_r($rows);
      return $rows;
    } catch(Exception $e) {
      return NULL;
    }
  }
  public function get_discussions_a($board_id) {
    try {
      $sql = "SELECT d.id,
                     d.title,
                     d.full_text,
                     d.creation_timestamp,
                     i.filename,
                     (SELECT COUNT(*)+1 FROM images, replies WHERE replies.discussion_id = d.id AND replies.image_id = images.id) AS image_count,
                     (SELECT COUNT(*) FROM replies WHERE replies.discussion_id = d.id) AS reply_count,
                     (SELECT creation_timestamp FROM replies WHERE replies.discussion_id = d.id ORDER BY id DESC LIMIT 1) AS last_reply_timestamp
              FROM discussions AS d,
                   images AS i
              WHERE d.board_id = :id AND
                    d.image_id = i.id AND
                    d.archived = 1";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $board_id);
      $stmt->execute();
      $rows = $stmt->fetchAll();
      //print_r($rows);
      return $rows;
    } catch(Exception $e) {
      return NULL;
    }
  }
  public function get_boards_x() {
    try {
      $sql = "SELECT b.id,
                     b.title,
                     b.fa_icon,
                     (SELECT COUNT(*) FROM discussions WHERE discussions.board_id = b.id) AS discussion_count,
                     (SELECT COUNT(*)+discussion_count FROM images, replies, discussions WHERE replies.discussion_id = discussions.id AND replies.image_id = images.id AND discussions.board_id = b.id) AS image_count,
                     (SELECT COUNT(*) FROM replies, discussions WHERE replies.discussion_id = discussions.id AND discussions.board_id = b.id) AS reply_count,
                     (SELECT creation_timestamp FROM discussions WHERE discussions.board_id = b.id ORDER BY id DESC LIMIT 1) AS last_discussion_timestamp,
                     (SELECT id FROM discussions WHERE discussions.board_id = b.id ORDER BY id DESC LIMIT 1) AS last_discussion_id,
                     (SELECT title FROM discussions WHERE discussions.board_id = b.id ORDER BY id DESC LIMIT 1) AS last_discussion_title,
                     (SELECT images.filename FROM images, discussions WHERE images.id = discussions.image_id AND discussions.board_id = b.id ORDER BY discussions.id DESC LIMIT 1) AS image_filename
              FROM boards AS b";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll();
      //print_r($rows);
      return $rows;
    } catch(Exception $e) {
      return NULL;
    }
  }
  public function get_replies($discussion_id) {
    try {
      $sql = "SELECT r.id,
                     r.full_text,
                     r.creation_timestamp,
                     i.filename
              FROM replies AS r
              LEFT JOIN images AS i
              ON r.image_id = i.id
              WHERE r.discussion_id = :id";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(':id', $discussion_id);
      $stmt->execute();
      $rows = $stmt->fetchAll();
      //print_r($rows);
      return $rows;
    } catch(Exception $e) {
      return NULL;
    }
  }
  public function get_statistics() {
    try {
      $sql = "SELECT (SELECT COUNT(*) FROM discussions) AS discussion_count,
                     (SELECT COUNT(*) FROM replies) AS reply_count,
                     (SELECT COUNT(*) FROM images) AS image_count,
                     (SELECT SUM(size) FROM images) AS image_size";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      $row = $stmt->fetch();
      return $row;
    } catch(Exception $e) {
      return NULL;
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
