<?php
class CouchDB {
  protected $socket;
  protected $host;
  protected $port;

  protected $user;
  protected $password;

  function __construct($host, $port, $user = NULL, $password = NULL) {
    $this->host = $host;
    $this->port = $port;
    $this->user = $user;
    $this->password = $password;
  }
  
  function send($method, $url, $post_data = NULL) {
    $s = fsockopen($this->host, $this->port, $errno, $errstr); 
    if(!$s) {
      return array (
        "error" => "$errno: $errstr\n",
      );
    } 

    $request = "$method $url HTTP/1.0\r\nHost: $this->host\r\n"; 

    if ($this->user) {
      $request .= "Authorization: Basic ".base64_encode("$this->user:$this->password")."\r\n"; 
    }

    if($post_data) {
      $request .= "Content-Length: ".strlen($post_data)."\r\n\r\n"; 
      $request .= "$post_data\r\n";
    } else {
      $request .= "\r\n";
    }

    fwrite($s, $request); 
    $response = ""; 

    while(!feof($s)) {
      $response .= fgets($s);
    }

    list($headers, $body) = explode("\r\n\r\n", $response); 
    return json_decode($body, true);
  }

  function select_all() {
    $result = $this->send('GET','/'.$this->database.
                            '/_design/'.$this->table.
                            '/_view/by_id');

    foreach ($result['rows'] as $row) {
      $all[] = $row['value'];
    }
    return $all;
  }

  function select($id) {
    return $this->select_clause($id, 'by_id');
  }

  function select_clause($id, $clause, $include_docs = false) {
    $result = $this->select_clause_array($id, $clause, $include_docs);
    if ($result) {
      return $result[0];
    } else {
      return null;
    }
  }

  function select_clause_array($id, $clause, $include_docs = false) {
    $inc = '';
    $datakey = 'value';
    if ($include_docs) {
      $inc = "include_docs=true&";
      $datakey = 'doc';
    }

    $result =  $this->send('GET','/'.$this->database.
                           '/_design/'.$this->table.
                           '/_view/'.$clause.'?'.$inc.'key='.
                            urlencode('"'.$id.'"'));
    foreach ($result['rows'] as $row) {
      $all[] = $row[$datakey];
    }
    return $all;
  }

  function save($id, $doc) {
   $id = urlencode($id);
   $doc['type'] = $this->table;
   return $this->send('PUT', '/'.$this->database.'/'.$id, json_encode($doc));
  }
 
}
