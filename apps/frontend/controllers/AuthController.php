<?php
/**
 * Created by PhpStorm.
 * User: lifarav
 * Date: 11.09.2016
 * Time: 20:25
 */

namespace Twet\Frontend\Controllers;

use Twet\Frontend\Models;
use Twet\Frontend\Models\Users;

/**
 * @property \Phalcon\Db\Adapter\Pdo\Mysql    $mysql   Компонент авторизации
 */

class AuthController extends ControllerBase
{

    public function indexAction()
    {

        if ($this->request->isPost()) {
            $email = $this->request->getPost('email', 'email');
            $password = $this->request->getPost('password');

            $user = Users::findFirstByEmail($email);

            // Если пользователь не найден, записывает событие N+1 и возвращаем на главную
            //TODO: Записать событие N+1
            if (!$user) {
                return $this->dispatcher->forward(
                    [
                        'controller' => 'index',
                        'action' => 'index'
                    ]
                );
            }

            if ($password == $user->password) {

            }

        }

        // Делаем переадресацию на главную и зарываем View, если пользователь не отправлял запрос
        //$this->dispatcher->forward([]);
        $this->view->setVars([
            'aa' => $user
        ]);
    }

}