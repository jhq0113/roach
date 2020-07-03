<?php
/**
 * Created by PhpStorm.
 * User: Jiang Haiqiang
 * Date: 2020/7/2
 * Time: 10:42 PM
 */
namespace roach\exceptions;

use roach\Roach;

/**
 * Class ErrorHandler
 * @package roach\exceptions
 * @datetime 2020/7/2 10:42 PM
 * @author roach
 * @email jhq0113@163.com
 */
class ErrorHandler extends Roach
{
    /**
     * @var callable
     * @datetime 2020/7/2 10:44 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public $handler;

    const FATAL_ERROR = [
        E_ERROR             => 1,
        E_PARSE             => 1,
        E_CORE_ERROR        => 1,
        E_CORE_WARNING      => 1,
        E_COMPILE_ERROR     => 1,
        E_COMPILE_WARNING   => 1,
    ];

    /**
     * @throws Exception
     * @datetime 2020/7/2 11:18 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public function run()
    {
        if(!is_callable($this->handler)) {
            throw new Exception('handler属性不可调用');
        }

        ini_set('display_errors', false);
        set_error_handler([$this, '_handlerError']);
        set_exception_handler([$this, '_handlerException']);
        register_shutdown_function([$this, '_handlerFatalError']);
    }

    /**
     * @param int    $code
     * @param string $message
     * @param string $file
     * @param int    $line
     * @datetime 2020/7/2 11:00 PM
     * @author roach
     * @email jhq0113@163.com
     */
    protected function _handlerError($code, $message, $file, $line)
    {
        if (error_reporting() & $code) {
            $exception = new \ErrorException($message, $code, 1, $file, $line);
            call_user_func($this->handler, $exception);
        }
    }

    /**
     * @param \Throwable $exception
     * @datetime 2020/7/2 11:08 PM
     * @author roach
     * @email jhq0113@163.com
     */
    protected function _handlerException($exception)
    {
        restore_error_handler();
        restore_exception_handler();

        call_user_func($this->handler, $exception);
    }

    /**
     * @datetime 2020/7/2 11:15 PM
     * @author roach
     * @email jhq0113@163.com
     */
    protected function _handlerFatalError()
    {
        $error = error_get_last();

        if(isset($error['type']) && !is_null(self::FATAL_ERROR[ $error['type'] ])) {
            $exception = new \ErrorException($error['message'], $error['type'], 1, $error['file'], $error['line']);
            call_user_func($this->handler, $exception);
        }
    }
}