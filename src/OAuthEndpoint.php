<?php

abstract class OAuthEndpoint extends Endpoint {

  public static function token() {
    $data = $_REQUEST;

    if (!isset($data['grant_type'])) {
      throw new Exception('error.token.missing_type', 400);
    }

    if ($data['grant_type'] != 'password') {
      throw new Exception('error.token.unsupported_type', 400);
    }

    if (!isset($data['username'])) {
      throw new Exception('error.token.missing_username', 400);
    }

    if (!isset($data['password'])) {
      throw new Exception('error.token.missing_password', 400);
    }

    try {
      $user = Flight::get('orm.em')->getRepository('User')->findOneBy(array(
        'username' => $data['username'],
      ));
    }
    catch (Exception $e) {
      throw new Exception('error.server');
    }

    if (is_null($user) || empty($data['password'])) {
      throw new Exception('error.token.bad_credentials', 400);
    }

    $permanent = password_verify($data['password'], $user->getPassword());
    $temporary = md5($data['password']) == $user->getTemporaryPassword();

    if (!$permanent && !$temporary) {
      throw new Exception('error.token.bad_credentials', 400);
    }

    $session = Session::create(array(
      'user' => $user,
      'opened' => new DateTime(),
      'token' => md5(uniqid()),
      'ip' => Flight::request()->ip,
      'ua' => Flight::request()->user_agent,
    ));

    static::send(array(
      'access_token' => $session->getToken(),
      'token_type' => 'bearer',
      'user_active' => $user->getActive(),
      'user_id' => $user->getId(),
      'user_role' => $user->getRole(),
    ));
  }

}
