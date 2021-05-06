<?php
class Article {
  function __construct($current_user)
  {
      $this->title = $_POST['title'];
      $this->body = $_POST['body'];
      $this->user_id = $current_user['id'];
  }
}