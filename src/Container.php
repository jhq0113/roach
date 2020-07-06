<?php
namespace roach;

use roach\extensions\IExtension;

/**
 * Class Container
 * @package roach
 * @datetime 2020/7/1 4:47 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class Container extends IExtension
{
    /**
     * @var array
     * @datetime 2019/8/30 15:49
     * @author roach
     * @email jhq0113@163.com
     */
    private static $_pool = [];

    /**放入容器
     * @param string $key
     * @param mixed  $value
     * @datetime 2019/8/30 15:51
     * @author roach
     * @email jhq0113@163.com
     */
    static public function set($key, $value)
    {
        self::$_pool[ $key ] = $value;
    }

    /**容器中获取对象
     * @param string $key
     * @return mixed
     * @throws \ReflectionException
     * @datetime 2020/7/1 10:49 PM
     * @author roach
     * @email jhq0113@163.com
     */
    static public function get($key)
    {
        if(!isset(self::$_pool[ $key ])) {
            return null;
        }

        //如果是配置数组
        if(is_array(self::$_pool[ $key ]) && isset(self::$_pool[ $key ]['class'])) {
            self::$_pool[ $key ] = self::createRoach(self::$_pool[ $key ]);
        }

        return self::$_pool[ $key ];
    }


    /**装配
     * @param object $object
     * @param mixed  $config
     * @throws \ReflectionException
     * @datetime 2020/7/2 11:48 PM
     * @author roach
     * @email jhq0113@163.com
     */
    static public function assem($object,$config)
    {
        foreach ($config as $property => $value) {
            $object->$property = static::insure($value);
        }
    }

    /**
     * @param array $config
     * @return object
     * @throws \ReflectionException
     * @datetime 2020/7/1 10:48 PM
     * @author roach
     * @email jhq0113@163.com
     */
    static public function createRoach(array $config)
    {
        $class = new \ReflectionClass($config['class']);
        unset($config['class']);

        $calls = [];
        if(isset($config['calls'])) {
            $calls = $config['calls'];
            unset($config['calls']);
        }

        //构造函数注入
        if(isset($calls['__construct'])) {
            $object = $class->newInstanceArgs($calls['__construct']);
            unset($calls['__construct']);
        } else {
            $object = $class->newInstance();
        }

        //属性注入
        self::assem($object, $config);

        //方法注入
        foreach ($calls as $key => $value) {
            if(is_numeric($key)) {
                if(is_array($value)) {
                    call_user_func_array([$object, $value['method']], $value['params']);
                } else {
                    call_user_func([ $object, $value]);
                }
                continue;
            }
            call_user_func_array([ $object, $key], $value);
        }

        return $object;
    }

    /**如果是对象配置则创建，否则原样返回
     * @param array|mixed  $config
     * @param string       $defaultClass
     * @return mixed
     * @throws \ReflectionException
     * @datetime 2020/7/1 10:48 PM
     * @author roach
     * @email jhq0113@163.com
     */
    static public function insure($config, $defaultClass='')
    {
        if(is_array($config)) {
            if(isset($config['class'])) {
                return self::createRoach($config);
            }

            if($defaultClass !== '') {
                $config['class'] = $defaultClass;
                return self::createRoach($config);
            }
        }

        return $config;
    }
}