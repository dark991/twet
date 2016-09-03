<?php
namespace Twet\Frontend\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Di;


class TwitchController extends ControllerBase
{

    public function beforeExecuteRoute($dispatcher)
    {

    }

    public function indexAction()
    {
        

        
    }

    public function callbackAction()
    {
        // Создаем сессию
        // Записываем куки
        // Помещаем все данные в авторизацию
        $this->response->setHeader('Cache-Control', 'private, max-age=0, must-revalidate');
        $this->response->redirect($this->config->hosts['main'] . '/');
        $this->view->disable();
        return;
    }
}