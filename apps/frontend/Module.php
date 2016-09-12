<?php

namespace Twet\Frontend;

use Phalcon\Http\Response\Cookies;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Crypt;
use Phalcon\Db\Adapter\Pdo\Mysql;
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
     */
    public function registerServices($di)
    {

        $config = (require '../apps/config/config.php');
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

        $di->setShared('customTag', function () use ($config) {
            $tags = new Library\TagComponent();
            $tags->setURL($config->hosts['main']);
            return $tags;
        });

        $di->set('dispatcher', function () {
            // Создаем менеджер событий
            $eventsManager = new EventsManager();
            // Плагин безопасности слушает события, инициированные диспетчером
            $eventsManager->attach('dispatch:beforeExecuteRoute', new Library\SecurityPlugin());
            // Отлавливаем исключения и not-found исключения, используя NotFoundPlugin
            $eventsManager->attach('dispatch:beforeException', new Library\NotFoundPlugin);

            $dispatcher = new Dispatcher();
            // Связываем менеджер событий с диспетчером
            $dispatcher->setEventsManager($eventsManager);
            $dispatcher->setDefaultNamespace("Twet\Frontend\Controllers\\");
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
                    \PDO::ATTR_CASE               => \PDO::CASE_LOWER
                ]
            ]);
        });

    }
}