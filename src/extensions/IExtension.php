<?php
namespace roach\extensions;

/**
 * Class IExtension
 * @package roach\extensions
 * @datetime 2020/7/1 4:48 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class IExtension
{
    /**扩展类不允许被实例化
     * IExtension constructor.
     */
    private function __construct()
    {
    }

    /**扩展类不允许被clone
     * @datetime 2020/7/1 4:49 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
}