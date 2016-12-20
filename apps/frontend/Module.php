<?php

namespace Twet\Frontend;

use Phalcon\DiInterface;
use Phalcon\Http\Response\Cookies;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Crypt;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\View;
use \Twet\Library;
use \Vendor;

class Module
{

    public function registerAutoloaders()
    {

        $loader = new Loader();
        $loader->registerNamespaces(array(
            'Twet\Frontend\Controllers' => '../apps/frontend/controllers/',
            'Twet\Frontend\Models' => '../apps/frontend/models/',
            'Twet\Frontend\Forms' => '../apps/frontend/forms/',
            'Twet\Library' => '../apps/library/',
            'Vendor\TwitchTV' => '../vendor/ritero/twitch-sdk/src/ritero/SDK/TwitchTV/',
        ));
        $loader->register();
    }

    /**
     * Register the services here to make them general or register in the ModuleDefinition to make them module-specific
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {

        $config = require_once '../apps/config/config.php';
        $di->setShared('config', $config);

        // Регистрация компонента TwitchSDK
        $di->setShared('twitch', function () use ($config) {
            return new Vendor\TwitchTV\TwitchSDK([
                'client_id' => $config->twitch->client_id,
                'client_secret' => $config->twitch->client_secret,
                'redirect_uri' => $config->twitch->redirect_uri,
            ]);
        });

        $di->set('crypt', function () use ($config) {
            $crypt = new Crypt();
            $crypt->setKey($config->crypt->key);
            return $crypt;
        });

        $di->setShared('cookies', function () {
            $cookies = new Cookies();
            $cookies->useEncryption(false);
            return $cookies;
        });

        $di->setShared('url', function () use ($config) {
            $url = new Url();
            $url->setBaseUri($config->hosts['main']);
            $url->setStaticBaseUri($config->hosts['static']);
            return $url;
        });

        $di->setShared('auth', function () use ($di) {
            $auth = new Library\AuthComponent($di);
            $auth->initialize();
            return $auth;
        });

        $di->set('dispatcher', function () use ($di){
            // Создаем менеджер событий
            $eventsManager = new EventsManager();
            $eventsManager->attach(
                'dispatch:beforeException',
                function($event, $dispatcher, $exception)
                {
                    switch ($exception->getCode()) {
                        case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                        case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                            $dispatcher->forward([
                                'controller' => 'error',
                                'action'     => 'route404'
                            ]);
                            return false;
                    }
                }
            );
            $dispatcher = new Dispatcher();
            // Связываем менеджер событий с диспетчером
            $dispatcher->setEventsManager($eventsManager);
            $dispatcher->setDefaultNamespace('Twet\Frontend\Controllers\\');
            return $dispatcher;
        });

        $di->set('view', function () use ($config) {
            $view = new View();
            $view->setViewsDir('../apps/frontend/views/');
            return $view;
        });

        $di->set('db', function () use ($config) {
            return new Mysql([
                'host' => $config->mysql['host'],
                'username' => $config->mysql['username'],
                'password' => $config->mysql['password'],
                'dbname' => $config->mysql['dbname'],
                'persistent' => $config->mysql['persistent'],
                'options' => [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
                    \PDO::ATTR_CASE => \PDO::CASE_LOWER
                ]
            ]);
        });

    }
}