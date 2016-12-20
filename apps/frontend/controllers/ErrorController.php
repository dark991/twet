<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 20.12.2016
 * Time: 20:25
 */

namespace Twet\Frontend\Controllers;

class ErrorController extends ControllerBase
{

    public function beforeExecuteRoute()
    {

    }

    public function Route404Action()
    {
        $this->response->setStatusCode(404, 'Not Found');
    }

}