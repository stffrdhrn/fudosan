<?php

class StringUtils {

  public static function strtoid($str) {
    $id = strtolower($str);
    $id = trim($id);
    $id = str_replace(" ", "-", $id);
    return $id;
  }

  public static function trim_last($str, $needle) {
    $pos = strrpos($str, $needle);
    if ($pos) {
      $str = substr($str, 0, $pos);
    }
    return $str;
  }

}
