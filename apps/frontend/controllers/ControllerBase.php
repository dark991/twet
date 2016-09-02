<?php
/**
 * Created by PhpStorm.
 * User: lifarav
 * Date: 03.09.2016
 * Time: 1:08
 */
namespace Twet\Frontend\Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    public function beforeExecuteRoute($dispatcher)
    {

    }

    protected function initialize()
    {
        $this->view->setTemplateBefore('container');
    }

    public function afterExecuteRoute($dispatcher)
    {


    }
}