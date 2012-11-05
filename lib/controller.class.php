<?php
  class Controller {
    # Variables that will be persisted in session during redirect
    protected $redirect_messages = array('error', 'success');

    protected $template;

    function __construct($action, $content_type = "html") {
      $model = get_class($this);
      $model = trim_last($model, 'Controller');
      
      $this->controller = strtolower($model); 
      $this->$model =& new $model;
      $this->Login =& new Login();
      $this->template =& new Template($this->controller, $action, $content_type);

      if(isset($_SESSION['login'])) {
        $this->set_login($_SESSION['login']);
      }
      $this->load_redirect_messages();

      $this->template->set('jsplugins', array());
      $this->template->set('title', $action);
      $this->redirected = false;
    }

    function save_redirect_messages() {
      foreach ($this->redirect_messages as $key) {
        $_SESSION[$key] = $this->template->get($key);
      }
    }   

    function load_redirect_messages() {
      foreach ($this->redirect_messages as $key) {
        if(isset($_SESSION[$key])) {
          $this->template->set($key, $_SESSION[$key]);
          unset($_SESSION[$key]);
        }
      }
    }   

    function set_login($loginid) {
      if($loginid) {
        $this->login = $this->Login->select($loginid);
        $this->template->set('login', $this->login);      
	
	$_SESSION['login'] = $loginid;
	$_SESSION['login_role'] = $this->login['role'];
      } else {
        $this->login = null;
        $this->template->set('login', null);
        
	$_SESSION['login'] = null;
	$_SESSION['login_role'] = null;
      }
      return $this->login;
    }

    function redirect($controller, $action, $id = null) {
      $this->save_redirect_messages();

      $this->redirected = true;
      header('Location: '. route($controller, $action, $id));
    }


    function action($action, $id = null) {
      $this->redirect($this->controller, $action, $id);
    }

    function set($key,$value) {
      $this->template->set($key,$value);
    }
    
    function __destruct() {
      if(!$this->redirected) {
        $this->template->render();
      }
    }
 }
