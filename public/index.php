<?php
/**
 * Created by PhpStorm.
 * User: lifarav
 * Date: 02.09.2016
 * Time: 20:32
 */

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;
//use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

try {

    // Регистрируем автозагрузчик

    $loader = new Loader();
    $loader->registerDirs([
        '../frontend/controllers/',
        '../frontend/models/'
    ])->register();

    // Создаем DI
    $di = new FactoryDefault();

    // Настраиваем компонент View
    $di->set('view', function () {
        $view = new View();
        $view->setViewsDir('../frontend/views/');
        return $view;
    });

    $application = new Application($di);

    $response = $application->handle();

    $response->send();

} catch (\Exception $e) {
    echo "Exception: ", $e->getMessage();
}