<?php

namespace Twet\Frontend;

use Phalcon\Loader;
use Phalcon\Acl\Adapter\Memory as Acl;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Db\Adapter\Pdo\Mysql;

class Module
{

    public function registerAutoloaders()
    {

        $loader = new Loader();
        $loader->registerNamespaces(array(
            'Twet\Frontend\Controllers' => '../apps/frontend/controllers/',
            'Twet\Frontend\Models' => '../apps/frontend/models/',
            'Twet\Library' => '../apps/library/',
            'TwitchTV' => '../vendor/ritero/twitch-sdk/src/ritero/SDK/TwitchTV/',
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
            return new \TwitchTV\TwitchSDK([
                'client_id' => $config->twitch->client_id,
                'client_secret' => $config->twitch->client_secret,
                'redirect_uri' => $config->twitch->redirect_uri,
            ]);
        });

        // Регистрация компонента TagComponent с абсолютными путями
        $di->setShared('customTag', function () use ($config) {
            $tags = new \Twet\Library\TagComponent();
            $tags->setURL($config->hosts['main']);
            return $tags;
        });

        //Registering a dispatcher
        $di->set('dispatcher', function () {
            $dispatcher = new Dispatcher();
            
            //Attach a event listener to the dispatcher
            $eventManager = new \Phalcon\Events\Manager();
            $eventManager->attach('dispatch', new Acl('frontend'));
            $dispatcher->setEventsManager($eventManager);
            $dispatcher->setDefaultNamespace("Twet\Frontend\Controllers\\");
            return $dispatcher;
        });

        //Registering the view component
        $di->set('view', function () {
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir('../apps/frontend/views/');
            return $view;
        });

        $di->set('db', function () {
            return new Database(array(
                "host" => "localhost",
                "username" => "root",
                "password" => "secret",
                "dbname" => "invo"
            ));
        });
    }
}