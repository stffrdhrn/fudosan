<?php
class ImageController extends Controller {
  function upload() {
    $this->set('title', "Upload image");

    if($_FILES && is_uploaded_file($_FILES['upload']['tmp_name'])) {
      $tmp_pic = $_FILES['upload']['tmp_name'];
      $name = $_FILES['upload']['name'];

      $tmb = tempnam('', 'img');
      $image = new Imagick($tmp_pic);
      $image->thumbnailImage(500, 400, true);
      $image->writeImage($tmb);

      $res = $this->Image->save_file('tmb.'.$name, $tmb);
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
    $picture = new Imagick();
    $picture->readImageBlob($model['data']);
    $picture->cropImage($_POST['width'], $_POST['height'], $_POST['x'], $_POST['y']);


    $tmp = tempnam('','img');
    $picture->writeImage($tmp);
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
