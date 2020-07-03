<?php
use roach\Container;
use roach\http\Request;
require __DIR__.'/bootstrap.php';

/**
 * @var Request $request
 */
$request = Container::createRoach([
    'class'  => 'roach\http\Request',
    'url'    => 'https://www.baidu.com',
    'params' => [
        'from' => time()
    ]
]);

$response = $request->get();
\roach\extensions\ECli::info($response->getBody());

$res = (new Request())
        ->setUrl('https://www.baidu.com')
        ->setTimeout(3)
        ->setParams([
            'userName' => 'sdfafd'
        ])
        ->post();
\roach\extensions\ECli::error($res->getBody());




