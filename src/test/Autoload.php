<?php

/**
 * Class Autoload
 * @datetime 2020/7/1 6:41 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class Autoload
{
    private function __construct()
    {
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * @var array
     * @datetime 2020/6/24 10:10 上午
     * @author   roach
     * @email    jhq0113@163.com
     */
    private static $_namespace = [];

    /**
     * @param string $prefix
     * @param string $dir
     * @datetime 2020/6/24 10:10 上午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function set($prefix, $dir)
    {
        self::$_namespace[ $prefix ] = $dir;
    }

    /**
     * @param array $namespaceArray
     * @datetime 2020/6/24 10:14 上午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function mset($namespaceArray = [])
    {
        foreach ($namespaceArray as $prefix => $dir) {
            self::$_namespace[ $prefix ] = $dir;
        }
    }

    /**
     * @param string $class
     * @datetime 2020/6/24 10:15 上午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function autoload($class)
    {
        $position = strpos($class,'\\');
        $prefix = substr($class, 0, $position);
        if(!isset(self::$_namespace[ $prefix ])) {
            return;
        }

        $fileName = self::$_namespace[ $prefix ].str_replace('\\','/', substr($class, $position)).'.php';
        if(file_exists($fileName)) {
            require $fileName;
        }
    }
}