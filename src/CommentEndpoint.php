<?php

abstract class CommentEndpoint extends ModelEndpoint {

  public static $model = 'Comment';

  public static function access($op, $model = NULL, $user = NULL) {
    $session = Flight::session();

    switch ($op) {
      case 'create':
        break;

      case 'delete':
        if (is_null($session)) {
          throw new Exception('error.resource.unauthenticated', 401);
        }

        if (!is_null($model) && !is_null($user)) {
          if ($user->getRole() != User::ROLE_ADMINISTRATOR) {
            if ($model->getPage()->getUser()->getId() != $user->getId()) {
              throw new Exception('error.resource.unauthorized', 403);
            }
          }
        }

        break;

      default:
        parent::access($op, $model, $user);
    }
  }

}
