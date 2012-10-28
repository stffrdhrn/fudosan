<?php
class Template {
  protected $variables = array();
  protected $view;
  protected $action;  
  protected $content_type;  

  function __construct($view,$action,$content_type = "html") {
    $this->view = $view;
    $this->action = $action;
    $this->content_type = $content_type;
  }

  function render() {
    if ($this->content_type == "json") {
      $this->render_json();
    } else if($this->content_type == "body") {
      $this->render_html(true);
    } else if($this->content_type == "data") {
      $this->render_data();
    } else {
      $this->render_html();
    }
  }

  function render_json() {
    extract($this->variables);
    $jsonPath = ROOT . '/app/view/json.php';

    $have_content = false;
    foreach(array($jsonPath) as $path) {
      if (file_exists($path)) {
        $have_content = true;
        require_once($path);
        break;
      }
    }
    
    if(!$have_content) {
      echo "No json view please create one in /app/view/json.php";
    }
  }

  function render_data() {
    extract($this->variables);
    $jsonPath = ROOT . '/app/view/data.php';

    $have_content = false;
    foreach(array($jsonPath) as $path) {
      if (file_exists($path)) {
        $have_content = true;
        require_once($path);
        break;
      }
    }
    
    if(!$have_content) {
      echo "No json view please create one in /app/view/data.php";
    }
  }

  function render_html($body_only = false) {
    extract($this->variables);
    $viewActionPath = ROOT . '/app/view/'.$this->view.'.'.$this->action.'.php';
    $actionPath = ROOT . '/app/view/'.$this->action.'.php';

    if (!$body_only) {
      require_once(ROOT.'/app/view/header.php');
    }
    foreach(array($viewActionPath, $actionPath) as $path) {
      if (file_exists($path)) {
        require_once($path);
        break;
      }
    }
    if (!$body_only) {
      require_once(ROOT.'/app/view/footer.php'); 
    }
  }
 
  function set($key,$value) {
    $this->variables[$key] = $value;    
  }

  function get($key) {
    if(isset($this->variables[$key])) {
      return $this->variables[$key];
    } else {
      return null;
    }
      
  }
}
