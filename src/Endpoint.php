<?php

abstract class Endpoint {
  
  public static function send($data, $code = 200) {
    if (get_called_class() != 'ErrorEndpoint') {
      try {
        Flight::get('orm.em')->flush();
      }
      catch (PDOException $e) {
        throw new Exception('error.server');
      }
    }

    try {
      Flight::response()->status($code);
    }
    catch (Exception $e) {
      Flight::response()->status(500);
    }

    Flight::response()
      ->header('Content-Type', 'application/json')
      ->write(JSONHelper::encode($data))
      ->send();
  }

}
