<?php
/**
 * Created by PhpStorm.
 * User: lifarav
 * Date: 12.09.2016
 * Time: 0:19
 */

namespace Twet\Frontend\Models;

use Phalcon\Mvc\Model;

class Session extends Model
{

    public $id;
    public $cookieValue;
    public $hash;
    public $userId;
    public $page;
    public $userAgent;
    public $remember;
    public $ip;
    public $timestamp;

    public function initialize()
    {
        $this->setSource('sessions');
    }
}