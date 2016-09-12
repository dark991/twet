<?php
/**
 * Created by PhpStorm.
 * User: lifarav
 * Date: 07.09.2016
 * Time: 21:53
 */

namespace Twet\Library;

use Phalcon\DiInterface;

class Component implements \Phalcon\DI\InjectionAwareInterface
{
    protected $_di;

    public function __construct(DiInterface $di)
    {
        $this->setDI($di);
    }

    public function setDI(DiInterface $di)
    {
        $this->_di = $di;
    }

    public function getDI()
    {
        return $this->_di;
    }
}