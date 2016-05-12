<?php

abstract class VideoEndpoint extends ModelEndpoint {

  public static $model = 'Video';

  public static function create() {
    static::access('create');

    $data = JSONHelper::decode(Flight::request()->getBody());

    if (!isset($data['finish'])) {
      throw new Exception('error.payload.malformed', 400);
    }

    $request = Flight::vimeo()->request($data['finish'], array(), 'DELETE');

    if ($request['status'] != 201) {
      throw new Exception('error.vimeo.failure');
    }

    $parts = explode('/', $request['headers']['Location']);
    $vid = array_pop($parts);

    $model = call_user_func(array(static::$model, 'create'), array(
      'vimeo_id' => $vid,
    ));

    if ($user = $model->getUser()) {
      Flight::vimeo()->request('/videos/' . $vid . '/tags/' . $user->getUsername(), array(), 'PUT');

      if ($school = $model->getUser()->getSchool()) {
        Flight::vimeo()->request('/videos/' . $vid . '/tags/' . $school->getUsername(), array(), 'PUT');
      }
    }

    static::send(array(
      $model::$singular => $model->serialize(),
    ), 201);
  }

  public static function ticket() {
    $request = Flight::vimeo()->request('/me/videos', array(
      'type' => 'streaming',
    ), 'POST');

    if ($request['status'] != 201) {
      throw new Exception('error.vimeo.general');
    }

    static::send(array(
      'upload' => $request['body']['upload_link_secure'],
      'finish' => $request['body']['complete_uri'],
    ));
  }

}
