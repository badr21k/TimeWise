<?php

define('VERSION', '0.7.0');

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));
define('APPS', ROOT . DS . 'app');
define('CORE', ROOT . DS . 'core');
define('LIBS', ROOT . DS . 'lib');
define('MODELS', ROOT . DS . 'models');
define('VIEWS', ROOT . DS . 'views');
define('CONTROLLERS', ROOT . DS . 'controllers');
define('LOGS', ROOT . DS . 'logs');
define('FILES', ROOT . DS. 'files');

// ---------------------  NEW DATABASE TABLE -------------------------
define('DB_HOST',         'gateway01.ap-northeast-1.prod.aws.tidbcloud.com');
define('DB_USER',         '29orPj4SnpUARYN.root'); 
// define('DB_PASS',         $_ENV['DB_PASS']);
define('DB_PASS',     'BLffq404LOa3TIrG');
define('DB_DATABASE',     'test');
define('DB_PORT',         '4000');


