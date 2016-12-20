<?php
/**
 * Created by PhpStorm.
 * User: lifarav
 * Date: 07.09.2016
 * Time: 21:53
 */

namespace Twet\Library;

use Phalcon\Mvc\Model;
use Twet\Frontend\Models\User;
use Twet\Frontend\Models\Session;

class AuthComponent extends Component
{

    const SESSION_COOKIE_NAME = 'Session';

    const SALT = '!9124dnsa0#_^s';

    protected $_sessionCookiePrefix = '';

    protected $_session;
    protected $_sign = '';
    protected $_user;

    protected $homeLoggedUrl = 'dashboard';

    protected $_isGuest = true;


    /**
     * Функция инициализации компонента авторизации
     */
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

        $this->_initialized = true;

    }


    /**
     * Получить название куки для сессии
     *
     * @return string
     */
    public function getSessionCookieName()
    {
        return $this->_sessionCookiePrefix . self::SESSION_COOKIE_NAME;
    }

    /**
     * Получить сессию из куков
     *
     * @return Session
     */
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

            $session = Session::findFirstByCookieValue($sessionData);

            if (!$session) {
                $this->closeSession();
                return $this->generateNewSession();
            }

            return $session;
        }

        return $this->generateNewSession();

    }

    /**
     * Создать новую сессию. Запись о сессии помещается в таблицу Sessions
     *
     * @return Session|\Exception
     */
    public function generateNewSession()
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
     * Установить куки для сессии
     *
     * @param string $cookieValue
     */
    public function setSessionCookie($cookieValue)
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


    /**
     * @param string $userId
     * @return Model
     */
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

    /**
     * Получить статус авторизации
     *
     * @return bool
     */
    public function isGuest()
    {
        return $this->_isGuest;
    }

    /**
     *  Сеттеры и геттеры
     */

    /**
     * Установить объект сессии
     *
     * @param Session $session
     * @return Session
     */
    public function setSession($session)
    {
        return $this->_session = $session;
    }

    /**
     * Вернуть объект сессии
     *
     * @return Session
     */
    public function getSession()
    {
        return $this->_session;
    }

    /**
     * Установить объект пользователя
     *
     * @param User $user
     * @return User
     */
    public function setUser($user)
    {
        return $this->_user = $user;
    }

    /**
     * Вернуть объект пользователя
     *
     * @return User
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * Получить подпись
     *
     * @return string
     */
    public function getSign()
    {
        return $this->_sign;
    }

    /**
     * Вернуть URL перенаправления после авторизации
     *
     * @return string
     */
    public function getHomeLoggedUrl()
    {
        return $this->homeLoggedUrl;
    }
}