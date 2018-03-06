<?php
class board
{
  private $icon;
  private $id;
  private $locked;
  private $title;

  function __construct()
  {
    $this->locked = 0;
    $this->id = 0;
  }
  public function get_icon() {
    return $this->icon;
  }
  public function set_icon($icon) {
    $this->icon = $icon;
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
