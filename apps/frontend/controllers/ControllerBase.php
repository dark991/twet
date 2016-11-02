<?php
/**
 * Created by PhpStorm.
 * User: lifarav
 * Date: 03.09.2016
 * Time: 1:08
 */
namespace Twet\Frontend\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Tag;

/**
 * @property \Twet\Library\AuthComponent        $auth               Компонент авторизации
 */


class ControllerBase extends Controller
{

    public $auth;

    public function beforeExecuteRoute()
    {
        /*$this->auth = $this->getDI()->getShared('auth');
        
        if($this->auth->isGuest()) {
            $this->response->redirect('auth/redirect?uri='.($this->request->getURI()));
        }
        $this->response->send();
        die();*/
    }

    protected function initialize()
    {
        $this->tag->setDoctype(Tag::XHTML5);
        $this->view->setTemplateBefore('container');
    }

    public function afterExecuteRoute()
    {

    }

    protected function _error($code = 404, $text = 'Not found')
    {
        $this->response->setStatusCode($code, $text)->sendHeaders();
        $this->dispatcher->forward([
            'controller' => 'error',
            'action' => '_404',
        ]);
    }
}