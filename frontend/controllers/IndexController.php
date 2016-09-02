<?php
/**
 * Created by PhpStorm.
 * User: lifarav
 * Date: 02.09.2016
 * Time: 20:52
 */
use Phalcon\Mvc\Controller;

class IndexController extends Controller
{

    public function indexAction()
    {
        echo "<h1>Привет!</h1>";
    }
}