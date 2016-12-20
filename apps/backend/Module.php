<?php

namespace Twet\Backend;

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher;
use Phalcon\DiInterface;

class Module
{
    public function registerAutoloaders()
    {
        $loader = new Loader();
        $loader->registerNamespaces(array(
            'Twet\Backend\Controllers' => '../apps/backend/controllers/',
            'Twet\Backend\Models'      => '../apps/backend/models/',
            'Twet\Library' => '../apps/library/',
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
        
        //Registering a dispatcher
        
        $di->set('dispatcher', function() {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('Twet\Backend\Controllers\\');
            return $dispatcher;
        });
        
        //Registering the view component
        
        $di->set('view', function() {
            $view = new View();
            $view->setViewsDir('../apps/backend/views/');
            return $view;
        });
        
    }
}