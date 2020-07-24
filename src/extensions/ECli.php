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

    /**
     * @param string $msg
     * @param array  $context
     * @datetime 2019/8/30 18:05
     * @author roach
     * @email jhq0113@163.com
     */
    static public function info($msg, array $context=[])
    {
        echo EString::interpolate("\033[1;32m ".date('Y-m-d H:i:s')." info: ".$msg." \033[0m".PHP_EOL, $context);
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
        echo EString::interpolate("\033[1;33m ".date('Y-m-d H:i:s')." warn: ".$msg." \033[0m".PHP_EOL, $context);
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
        echo EString::interpolate("\033[1;31m ".date('Y-m-d H:i:s')." warn: ".$msg." \033[0m".PHP_EOL, $context);
    }
}