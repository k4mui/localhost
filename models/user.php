<?php
class user
{
  private $account_status;
  private $email_address;
  private $id;
  private $password_hash;
  private $role;

  function __construct()
  {
    $this->id = 1; // not logged in
    $this->role = 0;
  }
  public function logout() {
    $this->id = 1;
    $this->role = 0;
    $this->email = NULL;
    $this->password = NULL;
  }
  public function get_account_status() {
    return $this->account_status;
  }
  public function set_account_status($account_status) {
    $this->account_status = $account_status;
  }
  public function get_email_address() {
    return $this->email_address;
  }
  public function set_email_address($email) {
    $this->email_address = $email;
  }
  public function get_id() {
    return $this->id;
  }
  public function set_id($id) {
    $this->id = $id;
  }
  public function get_password_hash() {
    return $this->password_hash;
  }
  public function set_password_hash($password_hash) {
    $this->password_hash = $password_hash;
  }
  public function get_role() {
    return $this->role;
  }
  public function set_role($role) {
    $this->role = $role;
  }
  public function is_admin() {
    return $this->role == 3;
  }
  public function is_anon() {
    return $this->role == 0;
  }
  public function is_mod() {
    return $this->role == 2;
  }
  public function is_registered() {
    return $this->role > 0;
  }
}

?>
