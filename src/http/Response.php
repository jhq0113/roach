<?php
namespace roach\http;

use roach\Roach;

/**
 * Class Response
 * @package roach\http
 * @datetime 2020/7/1 6:30 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class Response extends Roach
{
    /**
     * @var string
     * @datetime 2020/6/25 3:35 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    protected $_body;

    /**
     * @var array
     * @datetime 2020/7/1 6:31 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    protected $_info;

    /**
     * @param string $body
     * @return $this
     * @datetime 2020/6/25 4:05 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function setBody($body)
    {
        $this->_body = $body;
        return $this;
    }

    /**
     * @param array $info
     * @return $this
     * @datetime 2020/6/25 4:05 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function setInfo($info)
    {
        $this->_info = $info;
        return $this;
    }

    /**
     * @return array
     * @datetime 2020/6/25 4:17 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function getInfo()
    {
        return $this->_info;
    }

    /**
     * @param string $key
     * @return mixed
     * @datetime 2020/6/25 4:25 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function get($key)
    {
        return $this->_info[ $key ];
    }

    /**
     * @return bool
     * @datetime 2020/6/25 4:17 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function success()
    {
        return $this->get('http_code') === 200;
    }

    /**
     * @return string
     * @datetime 2020/6/25 4:11 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * @param bool $assoc
     * @return \stdClass|array
     * @datetime 2020/6/25 4:12 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function jsonBody($assoc = false)
    {
        return json_decode($this->_body, $assoc);
    }

    /**
     * @param bool $assoc
     * @return \SimpleXMLElement| array
     * @datetime 2020/6/25 4:15 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function xmlBody($assoc = false)
    {
        $result = simplexml_load_string($this->_body, 'SimpleXMLElement', LIBXML_NOCDATA);
        if(!$assoc) {
            return $result;
        }

        return json_decode(json_encode($result), $assoc);
    }
}