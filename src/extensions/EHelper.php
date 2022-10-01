<?php
/**
 * @description:公共帮助类
 * @author: jhq0113@163.com
 * @datetime: 2022/10/1 22:09
 */
namespace roach\extensions;

/**
 * @description:公共帮助类
 * @author: jhq0113@163.com
 * @datetime: 2022/10/1 22:10
 */
class EHelper extends IExtension
{
    /**
     * @var string
     */
    public static $REMOTE_ADDR_KEY = 'REMOTE_ADDR';

    /**
     * @var callable
     */
    public static $ipHandler;

    /**
     * @return string
     * @author: jhq0113@163.com
     * @datetime: 2022/10/1 19:34
     */
    public static function clientIp()
    {
        if(ECli::cli()) {
            return '127.0.0.1';
        }

        // 如果自定义了获取ip方法
        if(is_callable(static::$ipHandler)) {
            return call_user_func(static::$ipHandler);
        }

        return $_SERVER[ static::$REMOTE_ADDR_KEY ];
    }
}