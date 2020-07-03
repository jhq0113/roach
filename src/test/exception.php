<?php
use roach\Container;
require __DIR__.'/bootstrap.php';

Container::set('errorHandler', [
    'class' => 'roach\exceptions\ErrorHandler',
    'handler' => function(\Throwable $exception) {
        //打日志
        //报警。。。
        exit(json_encode([
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'message' => $exception->getMessage(),
        ],JSON_UNESCAPED_UNICODE).PHP_EOL);
    }
]);

/**
 * @var \roach\exceptions\ErrorHandler $errorHandler
 */
$errorHandler = Container::get('errorHandler');
//注册通用异常处理
$errorHandler->run();

//调用未定义方法
fun();
//$a =5/0;
