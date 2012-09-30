<?php
  class Controller {
    protected $template;

    function __construct($action, $content_type = "html") {
      $model = get_class($this);
      $model = trim_last($model, 'Controller');
      $view = strtolower($model);
      
      $this->$model =& new $model;
      $this->Login =& new Login();
      $this->template =& new Template($view,$action, $content_type);

      if(isset($_SESSION['login'])) {
        $this->set_login($_SESSION['login']);
      }
    }

    function set_login($loginid) {
      if($loginid) {
        $_SESSION['login'] = $loginid;
        $this->login = $this->Login->select($loginid);
        $this->template->set('login', $this->login);      
      } else {
        $_SESSION['login'] = null;
        $this->login = null;
        $this->template->set('login', null);
      }
      return $this->login;
    }

    function redirect($action) {
      $this->template->redirect($action);
    }

    function set($key,$value) {
      $this->template->set($key,$value);
    }
    
    function __destruct() {
      $this->template->render();
    }
 }
