<?php
class PropertyController extends Controller {
  function get($id) {
    $this->set('title', "Viewing property $id");
    $this->set('clients', $this->Property->select_clause_array($id, "by_id_logins", true));

    $model = $this->Property->select($id);
    if(isset($_SESSION['image_return']['image'])) {

      $image_id = $_SESSION['image_return']['image'];
      if(isset($model['images'])) {
          $model['images'][] = $image_id;
      } else {
          $model['images'] = array($image_id);
      }
      $this->Property->save($id, $model);
    }
    $_SESSION['image_return'] = array('controller' => $this->controller, 
                                      'action' => 'get',
                                      'id' => $id);
    $this->set('model', $model);
  }

  function listall($id = NULL) {
    if($id) {
      $this->set('title', "Listing your properties");
      $this->set('model', $this->Property->select_clause_array($id, 'by_login'));
    } else {
      $this->set('title', "Listing all properties");
      $this->set('model', $this->Property->select_all());
    }
  }

  function edit($id = NULL) {
    if ($id == NULL) {
      $this->set('title', "Creating new Property");
      $this->set('model', array());
    } else {
      $model = $this->Property->select($id);
      $this->set('title', "Editing property $id");
      $this->set('model', $model);
    }
  }

  function save() {
    $id = $_POST["_id"];
    if(!$id) {
      $id = strtoid($_POST["name"]);
    }
    $result = $this->Property->save($id, $_POST);
    if(isset($result["error"])) {
      $this->set('error', $error ); 
    } else {
      $this->set('success', $_POST['name'] . ' saved');
    }

#    var_dump($result);

    $this->action('get', $id);

    return $id;
  }

  # Link a client to this property
  function link($id) {
    $property = $this->Property->select($id);
    $loginid = $_GET['login'];

    $result = array();
    if ($property && $loginid) {
      # If not already linked, link it up and save
      if(!$property['clients'][$loginid]) {
        $login = array('_id' => $loginid);
        $property['clients'][$loginid] = $login;
        $result = $this->Property->save($id, $property);
      } else {
        $result['error'] = "This client is already linked";
      }
    } else {
      $result['error'] = "Property doesnt exist or loginid not provided";
    }
    $this->set('model', $result);
  }

  function unlink_client($id) {
    $property = $this->Property->select($id);
    $loginid = $_GET['login'];

    $result = array();
    if ($property && $loginid) {
      unset($property['clients'][$loginid]);
      $result = $this->Property->save($id, $property);
    } else {
      $result['error'] = "Property doesnt exist or loginid not provided";
    }
    $this->set('model', $result);
  }

  function unlink_image($id) {
    $property = $this->Property->select($id);
    $imageid = $_GET['image'];

    $result = array();
    if ($property && $imageid) {
      $images = array_diff($property['images'], array($imageid));
      $property['images'] = $images;
      $result = $this->Property->save($id, $property);
    } else {
      $result['error'] = "Property doesnt exist or imageid not provided";
    }
    $this->set('model', $result);
  }

}
