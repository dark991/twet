<?php
/**
 * Created by PhpStorm.
 * User: lifarav
 * Date: 03.09.2016
 * Time: 1:20
 */

namespace Twet\Library;

use Phalcon\Tag;

class TagComponent extends Tag
{

    /**
     * Создает HTML теги, включая абсолютный путь к ресурсу
     *
     */

    private $_url;

    public function setURL($url)
    {
        $this->_url = $url;
    }

    public function jsLink($parameters = null, $local = true)
    {
        return Tag::javascriptInclude($this->_url . $parameters, $local);
    }
    
    public function getURL()
    {
        return $this->_url;
    }

}