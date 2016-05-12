<?php

abstract class PictureEndpoint extends FileModelEndpoint {

  public static $model = 'Picture';

  public static function access($op, $model = NULL, $user = NULL) {
    $session = Flight::session();

    if (is_null($user) && !is_null($session)) {
      $user = $session->getUser();
    }

    switch ($op) {
      case 'create':
        break;

      case 'update':
      case 'delete':
        if (is_null($user) || $user->getRole() != User::ROLE_ADMINISTRATOR) {
          if (!is_null($model)) {
            if (is_null($session) && !is_null($model->getUser())) {
              throw new Exception('error.resource.unauthorized', 403);
            }
            if (!is_null($session) && $model->getuser() !== $user) {
              throw new Exception('error.resource.unauthorized', 403);
            }
          }
        }

        break;

      default:
        parent::access($op);
    }
  }

}
