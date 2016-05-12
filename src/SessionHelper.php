<?php

abstract class SessionHelper {

  public static function getActiveMessages($language = 'fr') {
    $models = Flight::get('orm.em')->getRepository('Message')->findBy(array(
      'active' => TRUE,
    ));

    $messages = array();
    $method = 'get' . ucfirst($language);

    foreach ($models as $model) {
      $messages[$model->getType()][] = call_user_func(array($model, $method));
    }

    return $messages;
  }

  public static function getActiveSession() {
    if (!isset($_SERVER['HTTP_AUTHORIZATION']) || empty($_SERVER['HTTP_AUTHORIZATION'])) {
      return;
    }

    $parts = explode(' ', $_SERVER['HTTP_AUTHORIZATION']);

    if (!isset($parts[0]) || !isset($parts[1]) || isset($parts[2]) || $parts[0] != 'Bearer') {
      throw new Exception('error.token.malformed', 400);
    }

    $session = Flight::get('orm.em')->getRepository('Session')->findOneBy(array(
      'token' => $parts[1],
    ));

    if (is_null($session)) {
      throw new Exception('error.token.invalid', 401);
    }

    return $session;
  }

}
