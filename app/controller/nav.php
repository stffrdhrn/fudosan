<?php
class NavController extends Controller {
  function home() {
    $this->set('title', 'Welcome');
  }

  function s403() {
    $this->set('title', '403 Forbidden');
  }

  function s404() {
    $this->set('title', '404 Not Found');
  }
}
