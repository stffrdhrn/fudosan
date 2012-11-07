<?php

# The app helper class provides some basic handler operations 
# to the framework for doing:
#   - Authentication
#   - Handle not_found
#   - Create urls for routing to controllers
#
# This needs to be app specific since, each app 
# might want to do things different. I.e. authentication
# might want to read some policy config.
# 
# Not found might want to redirect to the home page, not 404
#
class AppHelper {
  static $public = array(
   "login/start",
   "login/try_auth",
   "login/finish_auth",
   "login/logout",
   "nav/*"
  );

  static $protected = array(
   "property/listmine/%login%",
   "property/get/*",
   "login/edit/%login%",
   "image/get.data/*",
   "login/save"
  );


  public static function not_found($controller, $url) {
    error_log(" 404 - not found $url");
    $controller->redirect('nav', 's404');
  }

  public static function route($controller, $action, $id = null) {
    $url = 'index.php?url='.$controller.'/'.$action;

    if($id != null) {
      $url .= '/'.$id;
    }
    return $url;
  }



  # Returns the original URL if authorized
  # Returns the 403 forbidden URL if not-authorize
  # Returns the 404 not found URL if not found
  public static function validate_and_authorize($url) {

    # If no rule redirect to home
    if(!$url) {
      return 'nav/home';
    }

    $login_role = 'none';
    if (isset($_SESSION['login_role'])) {
      $login_role = $_SESSION['login_role'];
    }

    if ($login_role == 'admin' || $login_role == 'manager') {
      return $url;
    }
 
    foreach (self::$public as $pub_url) {
      if (fnmatch($pub_url, $url)) {
        return $url;
      }
    }

    if ($login_role == 'user') {
      $loginid = $_SESSION['login'];
      foreach (self::$protected as $protect_url) {
        $protect_url = str_replace('%login%', $loginid, $protect_url);

        if ($protect_url == $url || fnmatch($protect_url, $url)) {
          return $url;
        }
      }
    }

    error_log('Unauthorized URL access role('.$login_role.') ' . $url);
    return 'nav/s403';
  }

}
