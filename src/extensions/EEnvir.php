<?php
namespace roach\extensions;

/**
 * Class EEnvir
 * @package roach\extensions
 * @datetime 2020/7/1 5:24 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class EEnvir extends IExtension
{
    /**
     * 开发环境
     */
    const DEVELOP = 'develop';

    /**
     * 测试环境
     */
    const TEST    = 'test';

    /**
     * 生产环境
     */
    const PRODUCT = 'product';

    /**
     * @var string
     * @datetime 2020/7/1 5:25 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    private static $_envir;

    /**获取php.ini自定义配置项值
     * @param string   $key
     * @return string
     * @datetime 2019/8/30 18:17
     * @author roach
     * @email jhq0113@163.com
     */
    static public function iniConfig($key)
    {
        return get_cfg_var($key);
    }

    /**获取当前环境
     * @return string
     * @datetime 2019/8/30 18:17
     * @author roach
     * @email jhq0113@163.com
     */
    static public function envir()
    {
        if(is_null(self::$_envir)) {
            self::$_envir = static::iniConfig('envir');

            if(empty(self::$_envir)) {
                self::$_envir = self::DEVELOP;
            }
        }

        return self::$_envir;
    }

    /**
     * @return bool
     * @datetime 2020/7/1 5:28 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    static public function product()
    {
        return self::envir() === self::PRODUCT;
    }
}