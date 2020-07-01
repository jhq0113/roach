<?php
namespace roach\extensions;

/**
 * Class EString
 * @package roach\extensions
 * @datetime 2020/7/1 4:49 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class EString extends IExtension
{
    /**生成随机字符串
     * @param int    $length
     * @param string $seed
     * @return string
     * @datetime 2019/8/30 17:59
     * @author roach
     * @email jhq0113@163.com
     */
    static public function createRandStr($length, $seed = '123456789')
    {
        $str='';
        $cl=strlen($seed) - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $seed[ mt_rand(0, $cl) ];
        }
        return $str;
    }

    /**
     * @param string $message
     * @param array  $context
     * @param string $leftPlace
     * @param string $rightPlace
     * @return string
     * @example
     *
     * $message = '转账:账户{id}出现异常';
     * $context = [ 'id' => 56 ];
     * $message = EString::interpolate($message, $context);
     *
     * @datetime 2019/8/30 18:00
     * @author roach
     * @email jhq0113@163.com
     */
    static public function interpolate($message, array $context = [], $leftPlace='{', $rightPlace='}')
    {
        $replace = [];
        foreach ($context as $key => $val) {
            $replace[$leftPlace . $key . $rightPlace] = $val;
        }
        return strtr($message, $replace);
    }
}