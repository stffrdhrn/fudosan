<?php
class ImageController extends Controller {
  function upload() {
    $this->set('title', "Upload image");

    if($_FILES && is_uploaded_file($_FILES['upload']['tmp_name'])) {
      $tmp_pic = $_FILES['upload']['tmp_name'];
      $name = $_FILES['upload']['name'];
      $res = $this->Image->save_file($name, $tmp_pic);
      if (!$res) {
        $this->set('error', "No result from db");
      } else if (isset($res['error'])) {
        $this->set('error', $res['reason']);
      } else {
        $this->set('success', "uploaded $name");
      }
    }
  }

  function get($id) {
    $model = $this->Image->select_attachment($id);
    $this->set('model', $model);
  }
} 
