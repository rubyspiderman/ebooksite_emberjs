<?php

abstract class FileModelEndpoint extends ModelEndpoint {

  public static function create() {
    static::access('create');

    $data = Flight::request()->getBody();
    $file = tempnam(sys_get_temp_dir(), 'ESF');

    if (!file_put_contents($file, $data)) {
      throw new Exception('error.server', 500);
    }

    $model = call_user_func(array(static::$model, 'create'), array(
      'file_path' => $file,
    ));

    unlink($file);

    static::send(array(
      $model::$singular => $model->serialize(),
    ), 201);
  }

}
