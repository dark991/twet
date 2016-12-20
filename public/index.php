<?php

error_reporting(E_ALL);

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Application as BaseApplication;

class Application extends BaseApplication
{

    /**
     * Register the services here to make them general or register in the ModuleDefinition to make them module-specific
     */
    protected function registerServices()
    {
        $di = new FactoryDefault();

        $di->set('router', function () {
            $router = null;
            require_once __DIR__ . '/../apps/config/routes.php';
            return $router;
        });

        $this->setDI($di);
    }

    public function main()
    {
        $this->registerServices();

        $this->registerModules(array(
            'frontend' => array(
                'className' => 'Twet\Frontend\Module',
                'path' => '../apps/frontend/Module.php'
            ),

            'backend' => array(
                'className' => 'Twet\Backend\Module',
                'path' => '../apps/backend/Module.php'
            )
        ));

        echo $this->handle()->getContent();
    }
}

$application = new Application();
$application->main();