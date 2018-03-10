<?php
function mysql_timestamp_to_date($timestamp_from_db) {
    $timestamp = strtotime($timestamp_from_db);
    return date('d/m/Y', $timestamp);
}
function fancy_time($db_timestamp) {
  $time = date("Y-m-d H:i:s", strtotime($db_timestamp));
  $db = new DateTime($time);
  $curr = new DateTime();
  $interval = $curr->diff($db);
  if ($interval->d > 7) {
    return date("Y-m-d H:i", strtotime($db_timestamp));
  } else {
    if ($interval->d && $interval->h) {
      return $interval->d . " days and " . $interval->h . " hrs ago";
    }
    if ($interval->h && $interval->m) {
      return $interval->d . " days and " . $interval->h . " hrs ago";
    } else if ($interval->h) {
      return $interval->h . " hrs ago";
    } else if ($interval->m) {
      return $interval->m . " min ago";
    }
  }
}
?>
