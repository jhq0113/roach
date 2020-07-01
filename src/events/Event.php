<?php
namespace roach\events;

/**
 * Trait Event
 * @package roach\events
 * @datetime 2020/7/1 6:19 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
trait Event
{
    /**
     * @var array
     * @datetime 2019/8/31 1:02 PM
     * @author roach
     * @email jhq0113@163.com
     */
    private $_events = [];

    /**
     * 绑定事件
     * @param string     		$name
     * @param callable          $handler
     * @param bool 				$append
     * @datetime 2019/8/31 1:02 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public function on($name, $handler, $append = true)
    {
        /**
         * @var \SplQueue $queue
         */
        $queue = isset($this->_events[ $name ]) ? $this->_events[ $name ] : new \SplQueue();

        $append ? $queue->push($handler) : $queue->unshift($handler);
        $this->_events[ $name ] = $queue;
    }

    /**
     * 解绑事件
     * @param string     $name
     * @param null       $handler
     * @return bool
     * @datetime 2019/8/31 1:03 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public function off($name, $handler = null)
    {
        if (!isset($this->_events[ $name ])) {
            return true;
        }

        //移除所有$name事件的handler
        if ($handler === null) {
            unset($this->_events[ $name ]);
            return true;
        } else {
            /**
             * @var \SplQueue $queue
             */
            $queue = $this->_events[ $name ];
            foreach ($queue as $index => $eventHandler) {
                if ($eventHandler === $handler) {
                    $queue->offsetUnset($index);
                }
            }

            $this->_events[ $name ] = $queue;
        }

        return true;
    }

    /**
     * 是否有handler
     * @param string $name
     * @return bool
     * @datetime 2019/8/31 1:03 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public function hasHandlers($name)
    {
        if (isset($this->_events[ $name ]) && !($this->_events[ $name ]->isEmpty()) ) {
            return true;
        }

        return false;
    }

    /**
     * 触发事件
     * @param string  $name
     * @param null    $event
     * @datetime 2019/8/31 1:03 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public function trigger($name, $event = null)
    {
        if(!$this->hasHandlers($name)) {
            return;
        }

        if ($event === null) {
            $event = new EventObject();
        }
        $event->name = $name;

        foreach ($this->_events[ $name ] as $handler) {
            call_user_func($handler, $event);

            //标记已处理，停止后续处理
            if ($event->handled) {
                return;
            }
        }
    }
}