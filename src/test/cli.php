<?php
/**
 * Created by PhpStorm.
 * User: Jiang Haiqiang
 * Date: 2020/7/24
 * Time: 8:58 PM
 */

require __DIR__.'/bootstrap.php';

\roach\extensions\ECli::warn('警告:{info}', [
    'info' => 'warning'
]);

\roach\extensions\ECli::error('错误:{info}', [
    'info' => 'error'
]);

\roach\extensions\ECli::info('信息:{info}', [
    'info' => 'info'
]);