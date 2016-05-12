<?php

use Symfony\Component\Yaml\Parser;
use Vimeo\Vimeo;

abstract class VimeoHelper {

  protected static $instance;

  public static function getInstance() {
    if (!is_null(static::$instance)) {
      return static::$instance;
    }

    $parser = new Parser();

    try {
      $configuration = $parser->parse(file_get_contents(ROOT . '/config/vimeo.yml'));
    }
    catch (Exception $e) {
      throw new Exception('error.server');
    }

    return static::$instance = new Vimeo($configuration['id'], $configuration['secret'], $configuration['token']);
  }

}
