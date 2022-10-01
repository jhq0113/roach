<?php
/**
 * @description:数组帮助类
 * @author: jhq0113@163.com
 * @datetime: 2022/10/1 22:04
 */
namespace roach\extensions;

/**
 * @description:数组帮助类
 * @author: jhq0113@163.com
 * @datetime: 2022/10/1 22:04
 */
class EArray extends IExtension
{
    /**
     * @param array  $array
     * @param string $column
     * @param string $index
     * @return array
     * @author: jhq0113@163.com
     * @datetime: 2022/9/14 10:03
     */
    public static function column($array, $column, $index = null)
    {
        if(empty($array)) {
            return $array;
        }

        return array_column($array, $column, $index);
    }

    /**
     * @param array $array
     * @param string $indexKey
     * @return array
     * @author: jhq0113@163.com
     * @datetime: 2022/6/17 20:06
     */
    public static function index($array, $indexKey = 'id')
    {
        if(empty($array)) {
            return $array;
        }

        $list = [];
        foreach ($array as $item) {
            $list[ $item[ $indexKey ] ] = $item;
        }

        return $list;
    }

    /**
     * @param array $a
     * @param array $b
     * @return mixed
     * @author: jhq0113@163.com
     * @datetime: 2022/8/22 17:25
     */
    public static function merge($a, $b)
    {
        $args = func_get_args();
        $res = array_shift($args);
        while (!empty($args)) {
            foreach (array_shift($args) as $k => $v) {
                if (is_int($k)) {
                    if (array_key_exists($k, $res)) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = static::merge($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }

        return $res;
    }
}