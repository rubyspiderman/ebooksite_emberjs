<?php

abstract class JSONHelper {
  
  public static function decode($data) {
    $data = json_decode($data, TRUE);

    switch (json_last_error()) {
      case JSON_ERROR_NONE:
        return $data;
      case JSON_ERROR_DEPTH:
        throw new Exception('error.json.depth', 400);
      case JSON_ERROR_STATE_MISMATCH:
        throw new Exception('error.json.malformed', 400);
      case JSON_ERROR_CTRL_CHAR:
        throw new Exception('error.json.control_character', 400);
      case JSON_ERROR_SYNTAX:
        throw new Exception('error.json.syntax', 400);
      case JSON_ERROR_UTF8:
        throw new Exception('error.json.encoding', 400);
      default:
        throw new Exception('error.json.general', 400);
    }
  }

  public static function encode($data) {
    $data = json_encode($data);

    switch (json_last_error()) {
      case JSON_ERROR_NONE:
        return $data;
      default:
        throw new Exception('error.json.general', 500);
    }
  }

}
