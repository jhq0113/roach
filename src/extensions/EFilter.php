<?php
namespace roach\extensions;

/**
 * Class EFilter
 * @package roach\extensions
 * @datetime 2020/7/1 5:01 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class EFilter extends IExtension
{
    const TYPE_STR    = 'Str';
    const TYPE_INT    = 'Int';
    const TYPE_FLOAT  = 'Float';

    /**
     * @param string $key
     * @param array  $data
     * @param string $defaultValue
     * @param bool   $addslashes
     * @return string
     * @datetime 2020/7/1 5:05 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    static public function fStr($key, $data, $defaultValue = '', $addslashes = true)
    {
        if(!isset($data[ $key ])) {
            return $defaultValue;
        }

        $value = trim($data[ $key ]);

        return $addslashes ? addslashes($value) : $value;
    }

    /**过滤int参数
     * @param string $key           键
     * @param array  $data          数据数组
     * @param int    $defaultValue  默认值
     * @return int
     * @datetime 2019/9/19 18:52
     * @author roach
     * @email jhq0113@163.com
     */
    static public function fInt($key, $data, $defaultValue = 0)
    {
        if(!isset($data[ $key ])) {
            return $defaultValue;
        }

        return (int)trim($data[ $key ]);
    }

    /**过滤float参数
     * @param string $key           键
     * @param array  $data          数据数组
     * @param float  $defaultValue  默认值
     * @return float|int
     * @datetime 2019/9/19 18:51
     * @author roach
     * @email jhq0113@163.com
     */
    static public function fFloat($key, $data, $defaultValue = 0.0)
    {
        if(!isset($data[ $key ])) {
            return $defaultValue;
        }

        return (float)trim($data[ $key ]);
    }
}