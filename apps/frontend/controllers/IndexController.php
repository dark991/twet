<?php

namespace Twet\Frontend\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Di;

class IndexController extends ControllerBase
{

    public function beforeExecuteRoute()
    {

    }

    public function indexAction()
    {

        $twitch = $this->twitch;
        $loginURL = $twitch->authLoginURL('user_read');
        $channel = $twitch->channelGet('alex_lifar');
        var_dump($channel);

    }
}