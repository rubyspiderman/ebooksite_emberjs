<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Yaml\Parser;

abstract class ORMHelper {

  public static function getEntityManager() {
    $parser = new Parser();

    $configuration = Setup::createYAMLMetadataConfiguration(array(ROOT . '/config/orm'), FALSE, ROOT . '/cache');
    $database = $parser->parse(file_get_contents(ROOT . '/config/database.yml'));

    return EntityManager::create($database, $configuration);
  }

}
