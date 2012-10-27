<?php

function route($controller, $action, $id = null) {
  $url = 'index.php?url='.$controller.'/'.$action;

  if($id != null) {
    $url .= '/'.$id;
  }
  return $url;
}

function gravatar($model, $size=128) {
  if ($model['email']) { 
    $hash = md5( strtolower( trim( $model['email'] ) ) );
    return "<img class='img-rounded' alt='gravatar' src='http://www.gravatar.com/avatar/$hash?d=retro&s=$size&r=pg' width='$size' height='$size'/>";
  }
  return null;
}

function strtoid($str) {
  $id = strtolower($str);
  $id = trim($id);
  $id = str_replace(" ", "-", $id);
  return $id;
}

function parse_rest_url($url) {
  # Defaults
  $controller = 'nav';
  $action = 'home';
  $content_type = null;
  $query_string = null;

  if ($url) {
    # Parse URL (controller, $action, $query) = controller/action/query
    $p1 = strpos($url, '/');
    if ($p1) {
      $controller = substr($url, 0, $p1);
      $p2 = strpos($url, '/', $p1+1);
      if ($p2) {
        $action = substr($url, $p1+1, $p2-$p1-1);
        $query_string = substr($url, $p2+1);
      } else {
        $action = substr($url, $p1+1);
      }
    } else {
      $controller = $url;
    }
    # Parse URL complete
  }
  # if action contains something like action.json
  # then we want the stuff after the '.' to be content 
  # type
  $p1 = strpos($action, '.');
  if ($p1) {
    $content_type = substr($action, $p1+1);
    $action = substr($action, 0, $p1);
  }

  return array($controller, $action, $content_type, $query_string);
}

/** Main Call Function **/

/* 
 
 Name Conventions
  table : users
  view  : view/user.action.php
  model : model/user.php
  controller :
          controller/user.php

 */

function run() {
  global $url;
  session_start();

  # Parse our 3 part URL
  # Format: controller[/action[.type][/query_string]] 
  # Example: login/get/3
  # Example: login/listall.json
  list($controller, $action, $content_type, $query_string) = parse_rest_url($url);

  $controller = ucwords($controller).'Controller';
  $dispatch = new $controller($action, $content_type);

  if ((int)method_exists($controller, $action)) {
    call_user_func_array(array($dispatch,$action),array($query_string));
  } else {
    /* Hanlde errors */
  }
}

function trim_last($str, $needle) {
  $pos = strrpos($str, $needle);
  if ($pos) {
    $str = substr($str, 0, $pos);
  }
  return $str;
}

/** Autoload all classes that are required **/

function __autoload($className) {
  $controllerPath = ROOT . '/app/controller/'. strtolower(trim_last($className,'Controller')) . '.php';
  $modelPath = ROOT . '/app/model/'. strtolower($className) . '.php';

  foreach(array($controllerPath, $modelPath) as $path) {
    if (file_exists($path)) {
      require_once($path);
    }
  }
}

run();
