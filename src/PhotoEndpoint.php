<?php

abstract class PhotoEndpoint extends FileModelEndpoint {

  public static $model = 'Photo';

  public static function access($op, $model = NULL, $user = NULL) {
    switch ($op) {
      case 'create':
        break;
      default:
        return parent::access($op, $model, $user);
    }
  }

}
