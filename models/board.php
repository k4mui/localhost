<?php
class board
{
  private $fa_icon;
  private $id;
  private $locked;
  private $title;

  function __construct()
  {
    $this->locked = 0;
    $this->id = 0;
  }
  public static function with_row(& $row) {
    $instance = new self();
    $instance->set_id((int)$row["id"]);
    $instance->set_icon($row["fa_icon"]);
    $instance->set_title($row["title"]);
    $instance->set_locked((int)$row["locked"]);
    return $instance;
  }
  public function get_icon() {
    return $this->fa_icon;
  }
  public function set_icon($icon) {
    $this->fa_icon = $icon;
  }
  public function get_id() {
    return $this->id;
  }
  public function set_id($id) {
    $this->id = $id;
  }
  public function is_locked() {
    return $this->locked;
  }
  public function set_locked($locked) {
    $this->locked = $locked;
  }
  public function get_title() {
    return $this->title;
  }
  public function set_title($title) {
    $this->title = $title;
  }
}

?>
