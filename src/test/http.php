<?php
use roach\http\Request;
use roach\http\Response;
use roach\events\EventObject;
use roach\extensions\ECli;

//引入自动加载
require __DIR__.'/bootstrap.php';

//构建request对象
$request = (new Request())
            ->setUrl('http://www.baidu.com');

//绑定请求发送前事件
$request->on(Request::EVENT_BEFORE_REQUEST, function (EventObject $event) use($request) {
    ECli::info('trigger {event} and request {url}', [
        'event' => $event->name,
        'url'   => $request->url
    ]);
});

//绑定发送完请求事件
$request->on(Request::EVENT_AFTER_REQUEST, function (EventObject $event) {
    /**
     * @var Request $sendor
     */
    $sendor   = $event->sender;
    /**
     * @var Response $response
     */
    $response = $event->data;
    ECli::info('request {url} done, response {httpCode}', [
        'url'       => $sendor->url,
        'httpCode'  => $response->get('http_code')
    ]);
});

//发送请求
$resp= $request->get();

ECli::info('response {body}',[
    'body' => $resp->getBody()
]);