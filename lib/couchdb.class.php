<?php
class CouchDB {
  protected $socket;
  protected $host;
  protected $port;
  protected $debug = false;

  function __construct($host, $port, $user = NULL, $password = NULL) {
    $this->host = $host;
    $this->port = $port;

    $this->headers = array();
    $this->headers['Host'] = $this->host.':'.$this->port;
    $this->headers['User-Agent'] = 'php-couchdb-client/1.0 (shorne)';
    if($user) {
      $this->headers['Authorization'] = 'Basic ' . base64_encode($user.':'.$password);
    }
  }


  function send($method, $url, $post_data = NULL) {
    $s = fsockopen($this->host, $this->port, $errno, $errstr); 
    if(!$s) {
      return array (
        "error" => "$errno: $errstr\n",
      );
    } 

    $request = "$method $url HTTP/1.0\r\n"; 
    if($post_data && !isset($this->headers['Content-Length'])) {
      $this->headers['Content-Length'] = strlen($post_data);
    }
    foreach($this->headers as $header => $value) {
      $request .= $header.': '.$value."\r\n";
    }

    if($post_data) {
      $request .= "\r\n$post_data\r\n";
    } else {
      $request .= "\r\n";
    }

    if($this->debug) {
      echo "<label>request</label><pre>$request</pre>";
    }
    fwrite($s, $request); 
    $response = ""; 

    while(!feof($s)) {
      $response .= fgets($s);
    }
    fclose($s);
    if($this->debug) {
      echo "<label>response</label><pre>$response</pre>";
    }


    list($headers, $body) = explode("\r\n\r\n", $response); 
    return json_decode($body, true);
   
  }

  function put_file($url, $file) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $content_type = finfo_file($finfo, $file);
    finfo_close($finfo);

    $content_length = filesize($file);

    $s = curl_init();
    curl_setopt($s,CURLOPT_URL,'http://'.$this->host.':'.$this->port.$url);
    curl_setopt($s,CURLOPT_PUT,TRUE);
    curl_setopt($s,CURLOPT_HTTPHEADER,array('Content-Type: '.$content_type));
    curl_setopt($s,CURLOPT_RETURNTRANSFER,true); 
   
    $handle = fopen($file, 'r'); 
    curl_setopt($s,CURLOPT_INFILE, $handle);
    curl_setopt($s,CURLOPT_INFILESIZE, $content_length);

    $response = curl_exec($s);
    curl_close($s);
    fclose($handle);
    return json_decode($response, true);
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

    if(!isset($result['rows'])) {
      return null;
    }

    $all = array();
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
 
  function save_file($id, $file_loc) {

    $id = urlencode($id);
    $url =  '/'.$this->database.'/'.$this->table.'/'.$id;

    $doc = $this->send('GET','/'.$this->database.'/'.$this->table);
    if ($doc && isset($doc['_rev'])) {
      $url .= '?rev='.$doc['_rev'];
    }
    return $this->put_file($url,$file_loc);
  }

  function select_attachment($id) {
    $url_id = urlencode($id);
    $url =  '/'.$this->database.'/'.$this->table;
    $docs = $this->send('GET', $url);
    $attachment = null;
    if($docs && isset($docs['_attachments'][$id])) {
      $attachment = array();
      $attachment['content_type'] = $docs['_attachments'][$id]['content_type'];
      $attachment['data'] = file_get_contents("http://".$this->host.":".$this->port.$url.'/'.$url_id);
    }
    return $attachment;
  }
 
}
