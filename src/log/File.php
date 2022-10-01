<?php
/**
 * @description:file io
 * @author: jhq0113@163.com
 * @datetime: 2022/9/30 19:09
 */
namespace roach\log;

use roach\extensions\EHelper;
use roach\extensions\ECli;
use roach\extensions\EString;

/**
 * @description:file io 写日志
 * @author: jhq0113@163.com
 * @datetime: 2022/9/30 19:10
 */
class File extends ILogger
{
    /**
     * @var string
     */
    protected $_requestId;

    /**
     * @var string
     */
    public $fileName = '/tmp/log/jhq0113.log';

    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->_requestId = uniqid().'_'.EString::createRandStr(5);
    }

    /**
     * @param int    $level
     * @param string $message
     * @param array  $context
     * @return void|null
     * @author: jhq0113@163.com
     * @datetime: 2021/12/2 14:24
     */
    protected function _log($level, $message, array $context = array())
    {
        if($level > $this->level) {
            return;
        }

        if(ECli::cli()) {
            $data = [
                'clientIp'  => EHelper::clientIp(),
                'method'    => 'CLI',
                'host'      => $_SERVER['HOSTNAME']?:'127.0.0.1',
                'uri'       => implode(' ', $_SERVER['argv']),
            ];
        }else {
            $data = [
                'clientIp'  => EHelper::clientIp(),
                'method'    => $_SERVER['REQUEST_METHOD'],
                'host'      => $_SERVER['HTTP_HOST'],
                'uri'       => $_SERVER['REQUEST_URI'],
            ];
        }

        $data['requestId'] = $this->_requestId;
        $data['msg']       = EString::interpolate($message, $context);
        $data['level']     = self::$levelMap[ $level ];
        $data['dateTime']  = date('Y-m-d H:i:s');

        @file_put_contents($this->fileName, json_encode($data, 256|64).PHP_EOL, FILE_APPEND);
    }
}