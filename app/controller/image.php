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
    if(isset($_GET['w']) && isset($_GET['h'])) {
      $image = new Imagick();
      $image->readImageBlob($model['data']);

      $w = $_GET['w'];
      $h = $_GET['h'];
      $image->thumbnailImage($w, $h, true);
      $model['data'] = $image->getImageBlob();
    }

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

    $image_aspect = $image->getImageWidth() / $image->getImageHeight();
    $img_width = $_POST['img_width'];
    $img_height = $_POST['img_height'];
    if($image_aspect > 1) {
      $img_height = $img_width / $image_aspect;
    } else {
      $img_width = $img_height * $image_aspect;
    }

    $x_ratio = $image->getImageWidth() / $img_width;
    $y_ratio = $image->getImageHeight() / $img_height;

    $image->cropImage($_POST['width'] * $x_ratio, 
	              $_POST['height'] * $y_ratio, 
		      $_POST['x'] * $x_ratio, 
		      $_POST['y'] * $y_ratio);
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
