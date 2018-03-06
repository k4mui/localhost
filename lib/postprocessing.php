<?php
function mysql_timestamp_to_date($timestamp_from_db) {
    $timestamp = strtotime($timestamp_from_db);
    return date('d/m/Y', $timestamp);
}
?>
