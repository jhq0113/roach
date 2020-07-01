<?php
namespace roach\extensions;

/**
 * Class ECli
 * @package roach\extensions
 * @datetime 2020/7/1 5:09 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class ECli extends IExtension
{
    /**判断是否为Cli环境
     * @return bool
     * @datetime 2019/8/30 18:03
     * @author roach
     * @email jhq0113@163.com
     */
    static public function cli()
    {
        return PHP_SAPI === 'cli';
    }

    /**获取Cli参数
     * @return array
     * @datetime 2019/8/30 18:03
     * @author roach
     * @email jhq0113@163.com
     */
    static public function params()
    {
        return array_slice($_SERVER['argv'],1);
    }

    /**输出消息
     * @param string $msg
     * @datetime 2019/8/30 18:03
     * @author roach
     * @email jhq0113@163.com
     */
    static public function msg($msg)
    {
        echo '['.date('Y-m-d H:i:s').'] '. $msg. PHP_EOL;
    }

    /**
     * @param string $msg
     * @param array  $context
     * @datetime 2019/8/30 18:05
     * @author roach
     * @email jhq0113@163.com
     */
    static public function info($msg, array $context=[])
    {
        $msg = EString::interpolate($msg, $context);
        $msg = " \033[1;32m info:[ ".$msg." ]\033[0m";
        self::msg($msg);
    }

    /**
     * @param string $msg
     * @param array $context
     * @datetime 2019/8/30 18:05
     * @author roach
     * @email jhq0113@163.com
     */
    static public function warn($msg, array $context=[])
    {
        $msg = EString::interpolate($msg, $context);
        $msg = " \033[1;33m warn:[ ".$msg." ]\033[0m";
        self::msg($msg);
    }

    /**
     * @param string $msg
     * @param array $context
     * @datetime 2019/8/30 18:06
     * @author roach
     * @email jhq0113@163.com
     */
    static public function error($msg, array $context=[])
    {
        $msg = EString::interpolate($msg, $context);
        $msg = " \033[1;31m error:[ ".$msg." ]\033[0m";
        self::msg($msg);
    }
}