<?php
/**
 * Created by PhpStorm.
 * User: lifarav
 * Date: 07.09.2016
 * Time: 21:53
 */

namespace Twet\Library;

use Phalcon\Acl;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;

class SecurityPlugin extends Plugin
{

    const SESSION_COOKIE_NAME = 'Session';
    
    const CSRF_COOKIE_NAME = '_csrf';
    
    const ERROR_TO_MANY_ATTEMPTS = -2;
    const ERROR_LOGIN_FAILED = -1;
    
    const SALT = '!9124dnsa0#_^s';

    protected $_sessionCookiePrefix = '';

    protected $_session = '';
    protected $_sign = '';
    protected $_user = '';

    public $csrfToken = '';
    public $csrfTokenKey = 'csrfToken';

    protected $_isGuest = true;
    
    // Возвращает bool
    public function isGuest()
    {
        return $this->_isGuest;
    }

    // Получить название сессии
    public function getSessionCookieName()
    {
        return $this->_sessionCookiePrefix.self::SESSION_COOKIE_NAME;
    }
    
    // Получить сессию
    public function getSessionFromCookie()
    {
        
    }

    // Записать данные о сессии в БД
    // Обратиться к функции, создающей куки
    public function generateNewSession ()
    {
        $uniqid = uniqid(null, true);
        $this->setSessionCookie($uniqid);
    }

    /**
     * @param $sessionValue    Уникальное значение сессии
     */
    public function setSessionCookie ($sessionValue)
    {
        $cookies = $this->getDI()->getShared('cookies');
        $cookies->set(
            $this->getSessionCookieName(), // name
            $sessionValue, // value
            time() + 1 * 86400, // expire // 86400 - сутки
            '/', // path
            false, // secure (HTTPS)
            null, // domain
            true // httpOnly
        );
        $this->_isGuest = false;
    }

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        //return false;
    }

}