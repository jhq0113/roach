<?php
namespace roach\extensions;

/**
 * Class EHtml
 * @package roach\extensions
 * @datetime 2020/7/1 5:21 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class EHtml extends IExtension
{
    /**
     * @param string $content
     * @param bool   $doubleEncode
     * @return string
     * @datetime 2019/8/30 18:39
     * @author roach
     * @email jhq0113@163.com
     */
    static public function encode($content, $doubleEncode = true)
    {
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $doubleEncode);
    }

    /**
     * @param string $content
     * @return string
     * @datetime 2019/8/30 18:39
     * @author roach
     * @email jhq0113@163.com
     */
    static public function decode($content)
    {
        return htmlspecialchars_decode($content, ENT_QUOTES);
    }
}