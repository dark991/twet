<?php
/**
 * Created by PhpStorm.
 * User: lifarav
 * Date: 07.09.2016
 * Time: 21:53
 */

namespace Twet\Library;

use Phalcon\DiInterface;
use Phalcon\Di\InjectionAwareInterface;

class Component implements InjectionAwareInterface
{
    protected $_di;
    protected $_initialized;

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

    public function isInitialized()
    {
        return $this->_initialized;
    }

}