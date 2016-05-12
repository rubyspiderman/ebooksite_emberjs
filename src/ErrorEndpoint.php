<?php

abstract class ErrorEndpoint extends Endpoint {

  public static function exception(Exception $e) {
    $code = $e->getCode() ? $e->getCode() : 500;

    static::send(array(
      'meta' => array(
        'error' => $e->getMessage(),
      ),
    ), $code);
  }

}
