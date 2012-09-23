<?php
  class Model extends CouchDB {

    function __construct() {
      $this->connect(DB_HOST,DB_PORT);
      $this->database = DB_NAME;
      $this->table = strtolower(get_class($this));
    }
   function __destruct() {}

  }
