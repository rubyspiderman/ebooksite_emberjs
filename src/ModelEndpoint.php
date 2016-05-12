<?php

abstract class ModelEndpoint extends Endpoint {

  public static $model = 'Model';

  public static function access($op, $model = NULL, $user = NULL) {
    $session = Flight::session();

    if (is_null($user) && !is_null($session)) {
      $user = $session->getUser();
    }

    switch ($op) {
      case 'create':
        if (is_null($session)) {
          throw new Exception('error.resource.unauthenticated', 401);
        }

        break;

      case 'retrieve':
        break;

      case 'update':
      case 'delete':
        if (is_null($session)) {
          throw new Exception('error.resource.unauthenticated', 401);
        }

        if (!is_null($model)) {
          if (!method_exists($model, 'getUser')) {
            throw new Exception('error.resource.unauthorized', 403);
          }

          if (is_null($user) || $user->getRole() != User::ROLE_ADMINISTRATOR) {
            if ($model->getUser() !== $user && $model->getUser()->getSchool() !== $user) {
              throw new Exception('error.resource.unauthorized', 403);
            }
          }
        }

        break;

      case 'index':
        $model_variables = get_class_vars(static::$model);

        if (isset($model_variables['mapping']['filters']) && $model_variables['mapping']['filters']['required']) {
          $present = FALSE;

          foreach ($model_variables['mapping']['filters']['fields'] as $field) {
            if (isset(Flight::request()->query[$field])) {
              $present = TRUE;

              break;
            }
          }

          if (!$present) {
            if (is_null($session)) {
              throw new Exception('error.resource.unauthenticated', 401);
            }

            if ($user->getRole() != User::ROLE_ADMINISTRATOR) {
              throw new Exception('error.resource.unauthorized', 403);
            }
          }
        }

        break;

      default:
        throw new Exception('error.server');
    }
  }

  public static function create() {
    static::access('create');

    $data = JSONHelper::decode(Flight::request()->getBody());
    $model_variables = get_class_vars(static::$model);

    if (!isset($data[$model_variables['singular']])) {
      throw new Exception('error.payload.malformed', 400);
    }

    $model = call_user_func(array(static::$model, 'create'), $data[$model_variables['singular']]);

    static::send(array(
      $model::$singular => $model->serialize(),
    ));
  }

  public static function delete($model_id) {
    static::access('delete');

    $model = call_user_func(array(static::$model, 'retrieve'), $model_id);

    if (is_null($model)) {
      throw new Exception('error.resource.not_found', 404);
    }

    static::access('delete', $model);

    $model->delete();

    static::send(NULL, 204);
  }

  public static function index() {
    static::access('index');

    $filters = array();
    $model_variables = get_class_vars(static::$model);

    foreach ($model_variables['mapping']['filters']['fields'] as $field) {
      if (isset(Flight::request()->query[$field])) {
        $filters[$field] = Flight::request()->query[$field];
      }
    }

    $models = call_user_func(array(static::$model, 'index'), $filters);

    $serialized_models = array();

    foreach ($models as $model) {
      try {
        static::access('retrieve', $model);
      }
      catch (Exception $e) {
        continue;
      }

      if (method_exists($model, 'incrementViews')) {
        $model->incrementViews();
      }

      $serialized_models[] = $model->serialize();
    }

    if (!empty($filters) && empty($serialized_models)) {
      throw new Exception('error.resource.none_matching', 404);
    }

    static::send(array(
      $model_variables['plural'] => $serialized_models,
    ));
  }

  public static function retrieve($model_id) {
    static::access('retrieve');

    $model = call_user_func(array(static::$model, 'retrieve'), $model_id);

    if (is_null($model)) {
      throw new Exception('error.resource.not_found', 404);
    }

    static::access('retrieve', $model);

    if (method_exists($model, 'incrementViews')) {
      $model->incrementViews();
    }

    static::send(array(
      $model::$singular => $model->serialize(),
    ));
  }

  public static function update($model_id) {
    static::access('update');

    $data = JSONHelper::decode(Flight::request()->getBody());

    $model = call_user_func(array(static::$model, 'retrieve'), $model_id);

    if (is_null($model)) {
      throw new Exception('error.resource.not_found', 404);
    }

    static::access('update', $model);

    $key = strtolower(static::$model);

    if (!isset($data[$model::$singular])) {
      throw new Exception('error.payload.malformed', 400);
    }

    $model->update($data[$model::$singular]);

    static::send(array(
      $model::$singular => $model->serialize(),
    ));
  }

}
