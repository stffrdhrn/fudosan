<?php

$public = array(
   "login/start",
   "login/try_auth",
   "login/finish_auth",
   "login/logout",
   "nav/*"
);

$protected = array(
   "property/listmine/%login%",
   "property/get/*",
   "login/edit/%login%",
   "image/get.data/*",
   "login/save"
);

function is_authorized($url) {
  global $public;
  global $protected;

  $login_role = 'none';
  if(isset($_SESSION['login_role'])) {
    $login_role = $_SESSION['login_role'];
  }

  if ($login_role == 'admin' || $login_role == 'manager') {
    return true;
  }
 
  foreach($public as $pub_url) {
    if(fnmatch($pub_url, $url)) {
      return true;
    }
  }

  if ($login_role == 'user') {
    $loginid = $_SESSION['login'];
    foreach ($protected as $protect_url) {
      $protect_url = str_replace('%login%', $loginid, $protect_url);

      if ($protect_url == $url || fnmatch($protect_url, $url)) {
        return true;
      }
    }
  }

  error_log('Unauthorized URL access role('.$login_role.') ' . $url);
  return false;
}
