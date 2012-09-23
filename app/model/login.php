<?php
class Login extends Model {
  function select_by_openid($openid) {
    return $this->select_clause($openid, 'by_openid');
  }
}
