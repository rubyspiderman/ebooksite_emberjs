<?php

define('ROOT', getcwd());

include ROOT . '/vendor/autoload.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;

return ConsoleRunner::createHelperSet(ORMHelper::getEntityManager());
