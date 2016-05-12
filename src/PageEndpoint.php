<?php

abstract class PageEndpoint extends ModelEndpoint {

  public static $model = 'Page';

  public static function access($op, $model = NULL, $user = NULL) {
    if (!is_null($model) && !(!is_null($user) && $user->getRole == User::ROLE_ADMINISTRATOR)) {
      if (!$model->getUser()->getActive()) {
        throw new Exception('error.resource.unauthorized', 403);
      }
    }

    parent::access($op, $model, $user);
  }

}
