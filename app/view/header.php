<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $title ?></title>
    <meta charset="utf-8"/>
    <meta language="English"/>
    <link href="css/bassai.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
  </head>
  <body>
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
     <div class="container-fluid">
      <a class="brand" href="index.php">Bassai</a>
      <ul class="nav">
<?php if ($login) { ?>
        <li><a href="index.php?url=property/listall/<?php echo urlencode($login)?>">Properties</a>
        <li><a href="index.php?url=login/edit/<?php echo urlencode($login)?>">Preferenes</a>
        <li><a href="index.php?url=login/logout">Logout</a>
<?php } else { ?>
        <li><a href="index.php?url=login/start">Login</a>
<?php } ?>
      </ul>
    </div>
   </div>
  </div>
<div class="container-fluid">
  <div class="row-fluid">
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

