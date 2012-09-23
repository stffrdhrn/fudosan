<?php
  class Controller {
    protected $template;

    function __construct($action, $content_type = "html") {
      $model = get_class($this);
      $model = trim_last($model, 'Controller');
      $view = strtolower($model);
      
      $this->$model =& new $model;
      $this->template =& new Template($view,$action, $content_type);
    }

    function redirect($action) {
      $this->template->redirect($action);
    }

    function set($key,$value) {
      $this->template->set($key,$value);
    }
    
    function __destruct() {
      $this->template->set('login', $_SESSION['login']);
      $this->template->render();
    }
 }
