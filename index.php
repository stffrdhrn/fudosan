<?php 
define('ROOT', dirname(__FILE__));

if(isset($_GET['url'])) {
  $url = $_GET['url'];
}

require_once (ROOT.'/lib/bootstrap.php');
