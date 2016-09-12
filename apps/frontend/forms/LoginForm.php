<?php
/**
 * Created by PhpStorm.
 * User: lifarav
 * Date: 11.09.2016
 * Time: 20:37
 */

namespace Twet\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator;


class LoginForm extends Form
{

    public function initialize()
    {

        // E-Mail
        $email = new Email(
            'email',
            [
                'placeholder' => 'E-mail',
                'required' => 'required',
                'class' => 'input'
            ]
        );

        $email->addValidators([
            new Validator\PresenceOf([
                'message' => 'Это поле обязательно'
            ]),
            new Validator\Email([
                'message' => 'Необходимо ввести e-mail'
            ])
        ]);

        $this->add($email);
        
        // Password
        $password = new Password(
            'password',
            [
                'placeholder' => 'Пароль',
                'required' => 'required',
                'class' => 'input'
            ]
        );

        $password->addValidator(
            new Validator\PresenceOf(array(
                'message' => 'Это поле обязательно'
            ))
        );

        $this->add($password);

    }

    /**
     * Этот метод возвращает значение по умолчанию для поля 'csrf'
     */
    public function getCsrf()
    {
        return $this->security->getToken();
    }

}