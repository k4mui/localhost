<?php
function check_password($pwd, &$errors) {
  if (strlen($pwd) < 8) {
      $errors[] = "Password too short! Must be at least 8 characters long.";
  }
  if (!preg_match("#[0-9]+#", $pwd)) {
      $errors[] = "Password must include at least one number (0-9).";
  }
  if (!preg_match("#[A-Z]+#", $pwd)) {
      $errors[] = "Password must include at least one upper-case letter (A-Z).";
  }
  if (!preg_match("#[a-z]+#", $pwd)) {
      $errors[] = "Password must include at least one lower-case letter (A-Z).";
  }
}
function check_password_pair($pwd1, $pwd2, &$errors) {
  check_password($pwd1, $errors);
  if ($pwd1 !== $pwd2) {
      $errors[] = "Password and Confirm Password do not match";
  }
}
function check_email_address($email, & $errors) {
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "$email is not a valid email address.";
  }
}
?>
