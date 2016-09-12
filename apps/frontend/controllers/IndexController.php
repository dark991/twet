<?php

namespace Twet\Frontend\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Di;
use Twet\Frontend\Forms\LoginForm;

class IndexController extends ControllerBase
{

    public function indexAction()
    {

        $this->view->setVars([
            'form' =>  new LoginForm(),
        ]);

        /**$twitch = $this->twitch;
        $loginURL = $twitch->authLoginURL('user_read');
        $channel = $twitch->channelGet('alex_lifar');
        var_dump($channel);*/
    }



}