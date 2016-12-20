<?php
/**
 * Created by PhpStorm.
 * User: lifarav
 * Date: 03.09.2016
 * Time: 1:08
 */
namespace Twet\Backend\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

/**
 * @property \Twet\Library\AuthComponent $auth               Компонент авторизации
 */
class AjaxController extends Controller
{

    public $auth;

    public function beforeExecuteRoute()
    {

    }

    protected function initialize()
    {
        if ($this->request->isAjax()) {
            $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        } else {
            //$this->response->redirect();
        }
    }

    public function afterExecuteRoute()
    {

    }

}