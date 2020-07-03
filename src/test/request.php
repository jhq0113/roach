<?php
use roach\Container;
use roach\http\Request;
require __DIR__.'/bootstrap.php';

$res = (new Request())
    ->setUrl('https://www.baidu.com')
    ->setTimeout(3)
    ->setParams([
        'userName' => 'sdfafd'
    ])
    ->post();
\roach\extensions\ECli::error($res->getBody());

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


$req1 = Container::createRoach([
    'class'  => 'roach\http\Request',
    'url'    => 'https://www.360.cn',
]);

$req2 = Container::createRoach([
    'class'  => 'roach\http\Request',
    'url'    => 'http://www.sina.com',
    'params' => [
        'from' => time()
    ]
]);

$req3 = Container::createRoach([
    'class'  => 'roach\http\Request',
    'url'    => 'https://www.baidu.com',
    'params' => [
        'from' => time()
    ]
]);

$respList = Request::multiRequest($req1, $req2, $req3);
\roach\extensions\ECli::info($respList[0]->get('url'));
\roach\extensions\ECli::info($respList[1]->get('url'));
\roach\extensions\ECli::info($respList[2]->get('url'));

