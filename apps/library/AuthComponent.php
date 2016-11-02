<?php
/**
 * Created by PhpStorm.
 * User: lifarav
 * Date: 07.09.2016
 * Time: 21:53
 */

namespace Twet\Library;

use Phalcon\Mvc\Dispatcher;
use Phalcon\Http\Request;
use Twet\Frontend\Models\User;
use Twet\Frontend\Models\Session;

class AuthComponent extends Component
{

    const SESSION_COOKIE_NAME = 'Session';
    
    const SALT = '!9124dnsa0#_^s';

    protected $_sessionCookiePrefix = '';

    protected $_session = '';
    protected $_sign = '';
    protected $_user = '';
    
    public $homeLoggedUrl = 'dashboard';

    protected $_isGuest = true;
    
    public function initialize()
    {
        $session = $this->getSessionFromCookie();

        if (isset($session->userId)) {
            $user = $this->checkUserById($session->userId);
            
            if (!$user) {
                $this->logout();
            }
            
            $this->_sign = $session->hash;
            $this->_isGuest = false;
        }
        
        $this->setSession($session);
        //$this->setUser($user);

        $this->_initialized = true;
        
    }

    // Получить название сессии
    public function getSessionCookieName()
    {
        return $this->_sessionCookiePrefix.self::SESSION_COOKIE_NAME;
    }
    
    // Получить сессию или создать новую
    public function getSessionFromCookie()
    {
        $cookies = $this->getDI()->getShared('cookies');

        if ($cookies->has($this->getSessionCookieName())) {
            try {
                $sessionData = $cookies->get($this->getSessionCookieName())->getValue();
            } catch (\Exception $e) {
                $this->clearCookies();
                return $this->generateNewSession();
            }
            
            $session = Session::findFirstByTimestamp($sessionData);
            if(!$session) {
                $this->closeSession();
                return $this->generateNewSession();
            }
            
            return $session;
        }

        return $this->generateNewSession();

    }

    /**
     * Создать новую сессию. Запись о сессии помещается в таблицу Sessions
     */
    public function generateNewSession ()
    {
        $request = $this->getDI()->getShared('request');

        $uniqid = uniqid(null, true);
        $session = new Session();
        $session->cookieValue = $uniqid;
        $session->page = '/';
        //TODO Жесткий костыль с логированием useragent'а
        $session->userAgent = substr($request->getUserAgent(), 0, 255);
        $session->remember = 0;
        $session->ip = $request->getClientAddress();
        $session->timestamp = null;

        try {
            $session->save();
        } catch (\Exception $e) {
            return $e; // выскочит тогда, когда поля выше не заполнены!!!
        }

        $this->setSessionCookie($uniqid);
        return $session;
    }

    /**
     * @param $cookieValue    Уникальное значение сессии
     */
    public function setSessionCookie ($cookieValue)
    {
        $cookies = $this->getDI()->getShared('cookies');
        $cookies->set(
            $this->getSessionCookieName(), // name
            $cookieValue, // value
            time() + 1 * 86400, // expire // 86400 - сутки
            '/', // path
            false, // secure (HTTPS)
            null, // domain
            true // httpOnly
        );
        $this->_isGuest = false;
    }

    public function checkUserById($userId)
    {
        return User::findFirst($userId);
    }

    /**
     * Закрыть пользовательскую сессию.
     */
    public function closeSession()
    {
        $this->clearCookies();
    }

    /**
     * Очистить сессионные куки пользователя
     */
    public function clearCookies()
    {
        $this->getDI()->getShared('cookies')->delete($this->getSessionCookieName());
    }

    /**
     * Разлогинить текущего пользователя
     */
    public function logout()
    {
        $this->closeSession();
    }

    public function isGuest()
    {
        return $this->_isGuest;
    }
    
    /**
     *  Сеттеры и геттеры
     */

    /**
     * @param $session
     * @return mixed
     */
    public function setSession($session)
    {
        return $this->_session = $session;
    }
    
    public function getSession()
    {
        return $this->_session;
    }

    /**
     * @param $user
     * @return mixed
     */
    public function setUser($user)
    {
        return $this->_user = $user;
    }

    public function getUser()
    {
        return $this->_user;
    }
    
    public function sign()
    {
        
        
    }

    public function getSign()
    {
        return $this->_sign;
    }
}