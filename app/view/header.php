<!DOCTYPE html>
<html>
  <head>
    <title>Sabaai : <?php echo $title ?></title>
    <meta charset="utf-8"/>
    <meta language="English"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bassai.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
<?php foreach ($jsplugins as $jsplugin) { ?>
    <link href="css/<?php echo $jsplugin ?>.css" rel="stylesheet">
<?php } ?>
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
       <a class="brand logo" href="index.php" title="Sabaai">Sabaai</a>
       <div class="nav-collapse collapse">
        <ul class="nav">
<?php if (isset($login)) { ?>
        <li class="<?php if($this->view == 'property') {echo 'active' ; } ?>"><a href="<?php echo route('property', 'listall', urlencode($login['_id'])) ?>">Properties</a>
        <li class="<?php if($this->view == 'login') {echo 'active' ; } ?>" ><a href="<?php echo route('login','edit',  urlencode($login['_id']))?>">Preferences</a>
        <li class=""><a href="<?php echo route('login', 'logout') ?>">Logout</a>
<?php } else { ?>
        <li class="<?php if($this->view == 'login') {echo 'active' ; } ?>"><a href="<?php echo route('login', 'start') ?>">Login</a>
<?php } ?>
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

