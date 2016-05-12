<?php

use \Doctrine\Common\Collections\Criteria;

class UserEndpoint extends ModelEndpoint {

  public static $model = 'User';

  public static function access($op, $model = NULL, $user = NULL) {
    $session = Flight::session();

    if (is_null($user) && !is_null($session)) {
      $user = $session->getUser();
    }

    switch ($op) {
      case 'create':
        break;

      case 'retrieve':
        if (is_null($user) || $user->getRole() != User::ROLE_ADMINISTRATOR) {
          if (!is_null($model) && !is_null($user)) {
            if ($model == $user && !$model->getActive()) {
              throw new Exception('error.resource.inactive', 402);
            }
          }
        }

        break;

      case 'update':
      case 'delete':
        if (is_null($user) || $user->getRole() != User::ROLE_ADMINISTRATOR) {
          if (is_null($session)) {
            throw new Exception('error.resource.unauthenticated', 401);
          }

          if (!is_null($model)) {
            if ($user !== $model) {
              throw new Exception('error.resource.unauthorized', 403);
            }
          }
        }

        break;

      case 'index':
        if (isset(Flight::request()->query['role'])) {
          $role = Flight::request()->query['role'];

          if ($role != User::ROLE_SCHOOL && is_null($session)) {
            throw new Exception('error.resource.unauthorized', 403);
          }

          if (!($role == User::ROLE_INSTRUCTOR || $role == User::ROLE_SCHOOL)) {
            throw new Exception('error.resource.unauthorized', 403);
          }
        }
        elseif (is_null($session)) {
          throw new Exception('error.resource.unauthenticated', 401);
        }
        elseif ($user->getRole() != User::ROLE_ADMINISTRATOR) {
          throw new Exception('error.resource.unauthorized', 403);
        }

        break;

      default:
        parent::access($op, $model, $user);
    }
  }

  public static function forgot() {
    $data = $_REQUEST;

    if (!isset($data['identifier'])) {
      throw new Exception('error.field.identifier.required', 400);
    }

    try {
      $criteria = Criteria::create()
        ->where(Criteria::expr()->eq('username', $data['identifier']))
        ->orWhere(Criteria::expr()->eq('mail', $data['identifier']));

      $result = Flight::get('orm.em')->getRepository('User')->matching($criteria);
    }
    catch (Exception $e) {
      throw new Exception('error.server' . $e->getMessage());
    }

    if ($result->isEmpty()) {
      throw new Exception('error.resource.not_found', 404);
    }

    $result->first()->resetPassword();

    static::send((object) array());
  }

  public static function index() {
    Flight::request()->query['active'] = TRUE;

    parent::index();
  }

}
