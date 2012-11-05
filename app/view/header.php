<!DOCTYPE html>
<?php

  function nav_li($tmpl, $view, $action, $text, $id = null) {
    $active = '';
    if($tmpl->view == $view && $tmpl->action == $action) {
      $active = 'active';
    }
    return '<li class="'.$active.'"><a href="'.route($view, $action, $id).'">'.$text.'</a></li>';
  }
?>

<html>
  <head>
    <title>Sabaai : <?php echo $title ?></title>
    <meta charset="utf-8"/>
    <meta language="English"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/css/bootstrap-combined.min.css" rel="stylesheet">
<?php foreach ($jsplugins as $jsplugin) { ?>
    <link href="css/<?php echo $jsplugin ?>.css" rel="stylesheet">
<?php } ?>
    <link href="css/bassai.css" rel="stylesheet">
  </head>
  <body>
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">

     <div class="container">
       <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
       </button>
       <a class="brand" href="index.php" title="Sabaai">Sabaai</a>
       <div class="nav-collapse collapse">
        <ul class="nav">
<?php if (isset($login)) { 
  if ($login['role'] == 'user') { 
    echo nav_li($this, 'property', 'listmine','My Properties',  urlencode($login['_id']));
    echo nav_li($this, 'login', 'edit', 'My Preferences', urlencode($login['_id']));
  } else {
    echo nav_li($this, 'property', 'listall', 'Manage Properties');
    echo nav_li($this, 'login', 'listall', 'My Clients');
    echo nav_li($this, 'login', 'edit', 'My Preferences', urlencode($login['_id']));
  }
  echo nav_li($this, 'login', 'logout', 'Logout');
} else { 
  echo nav_li($this, 'login', 'start', 'Login');
} ?>
        </ul>
       </div>
     </div>

   </div>
  </div>
<?php if (isset($error)) { ?>
<div class="alert alert-error">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <h4>Error</h4>
  <?php echo $error ?>
</div>
<?php } ?>
<?php if (isset($success)) { ?>
<div class="alert alert-success">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <h4>Success</h4>
  <?php echo $success ?>
</div>
<?php } ?>

