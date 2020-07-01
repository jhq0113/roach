<?php
namespace roach\extensions;

/**
 * Class EOpcache
 * @package roach\extensions
 * @datetime 2020/7/1 5:22 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class EOpcache extends IExtension
{
    /**检测是否开启了opcache
     * @return bool
     * @datetime 2019/8/30 18:10
     * @author roach
     * @email jhq0113@163.com
     */
    static public function enable()
    {
        return function_exists('opcache_get_status');
    }

    /**重新校验所有文件
     * @datetime 2019/8/30 18:14
     * @author roach
     * @email jhq0113@163.com
     */
    static public function recheck()
    {
        if(!self::enable()) {
            return;
        }

        $info = opcache_get_status();
        if(isset($info['scripts'])) {
            foreach ($info['scripts'] as $file => $value) {
                opcache_invalidate($file);
            }
        }
    }

    /**优先使用recheck,非特殊情况不建议使用
     * @datetime 2019/8/30 18:13
     * @author roach
     * @email jhq0113@163.com
     */
    static public function restart()
    {
        if(!self::enable()) {
           return;
        }

        opcache_reset();
    }
}