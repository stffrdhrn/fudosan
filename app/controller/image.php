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
	$this->action('resize', $name);
      }
    }
  }

  function get($id) {
    $model = $this->Image->select_attachment($id);
    $this->set('model', $model);
  }

  function resize($id) {
    $this->set('title', "Image resize");
    $this->set('id', $id);
    $this->set('jsplugins', array('imagecrop'));
  }

  function crop($id) {
    $model = $this->Image->select_attachment($id);
    $image = new Imagick();
    $image->readImageBlob($model['data']);
    $image->cropImage($_POST['width'], $_POST['height'], $_POST['x'], $_POST['y']);
    $image->thumbnailImage(640, 480, true);


    $tmp = tempnam('','img');
    $image->writeImage($tmp);
    $res = $this->Image->save_file($id, $tmp);
    if (!$res) {
      $this->set('error', "No result from db");
    } else if (isset($res['error'])) {
      $this->set('error', $res['reason']);
    } else {
      $_SESSION['image_return']['image'] = $id;
      $return = $_SESSION['image_return'];
      $this->redirect($return['controller'], $return['action'], $return['id']);
    }
  }
} 
