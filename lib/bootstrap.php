<?php
/* Load config */
require_once (ROOT.'/app/config/infra.php');
require_once (ROOT.'/app/config/helper.php');

/* Load framework classes */
require_once (ROOT.'/lib/stringutils.php');
require_once (ROOT.'/lib/controller.class.php');
require_once (ROOT.'/lib/couchdb.class.php');
require_once (ROOT.'/lib/model.class.php');
require_once (ROOT.'/lib/template.class.php');

/* Require the OpenID consumer code. */
$path = ini_get('include_path');
$path = ROOT.'/lib/openid-php' . PATH_SEPARATOR . $path;
ini_set('include_path', $path);
require_once "Auth/OpenID/Consumer.php";
require_once "Auth/OpenID/CouchDBStore.php";
require_once "Auth/OpenID/SReg.php";
require_once "Auth/OpenID/PAPE.php";


/* go */
require_once (ROOT.'/lib/shared.php');

