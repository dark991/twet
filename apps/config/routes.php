<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 20.12.2016
 * Time: 19:49
 */

use Phalcon\Mvc\Router;

$router = new Router();

$router->setDefaultModule('Twet');
$router->setDefaultModule('frontend');
$router->setDefaultController('index');
$router->setDefaultAction('index');

/**
 * Backend routings
 */

$router->add('/signin', [
    'module' => 'backend',
    'controller' => 'signin'
]);

/**
 * Frontend routings
 */

$router->add('/:controller/:action', [
    'module' => 'frontend',
    'controller' => 1,
    'action' => 2
]);

$router->add('/twitch/:action', [
    'module' => 'frontend',
    'controller' => 'twitch',
    'action' => 1
]);

return $router;