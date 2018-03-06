<?php

/**
 *
 */
class discussion
{
  private $discussion_id;
  private $discussion_title;
  private $image_count;
  private $post_count;
  private $first_post_full_text;
  private $creation_timestamp;
  private $last_post_timestamp;


  function __construct()
  {
  }

  public function set_discussion_id($discussion_id) {
    $this->discussion_id = $discussion_id;
  }
}

?>
