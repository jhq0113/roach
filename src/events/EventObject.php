<?php
namespace roach\events;

use roach\Roach;

/**
 * Class EventObject
 * @package roach\events
 * @datetime 2020/7/1 6:17 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class EventObject extends Roach
{
    /**事件名称
     * @var string $name
     * @datetime 2019/8/31 1:05 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public $name;

    /**事件触发者
     * @var object $sender
     * @datetime 2019/8/31 1:05 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public $sender;

    /**事件是否已经被处理，如果handled为true,其他handler不会再接收到通知
     * @var bool
     * @datetime 2019/8/31 1:04 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public $handled = false;

    /**事件携带的数据
     * @var mixed
     * @datetime 2019/8/31 1:04 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public $data;
}