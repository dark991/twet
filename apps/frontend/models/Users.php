<?php
/**
 * Created by PhpStorm.
 * User: lifarav
 * Date: 12.09.2016
 * Time: 0:19
 */

namespace Twet\Frontend\Models;

use Phalcon\Mvc\Model;

class Users extends Model
{

    /*protected $id;
    protected $email;
    protected $password;
    protected $twitch_token;
    protected $datetime;*/

    public $id;
    public $email;
    public $password;
    public $twitch_token;
    public $datetime;

    /*public function initialize()
    {
        $this->setSource('users');
    }*/

    /*public function getId()
    {
        return $this->id;
    }*/
}