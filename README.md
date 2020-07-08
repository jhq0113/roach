# roach

> roach是一个基于composer开发的一个基础工具库，可以嵌入到任何php语言开发的项目当中。

> roach使用简单，精简，整个代码库纯代码大小为`60K`。

```text
8.0K    ./src/exceptions
 28K    ./src/extensions
 16K    ./src/http
8.0K    ./src/events
```

# 我的官方网站

[https://404.360tryst.com/](https://404.360tryst.com/)

# 安装方式

```bash
composer require jhq0113/roach
```

<!-- TOC -->
# 目录

- [1.容器](#容器) 
     - [1.1依赖注入](#依赖注入)
     - [1.2依赖注入容器](#依赖注入容器)
     - [1.3变量容器](#变量容器)
- [2.通用异常错误处理](#通用异常错误处理)
- [3.使用事件](#使用事件)
- [4.发送HTTP请求](#发送HTTP请求)
     - [4.1连贯操作发送请求](#连贯操作发送请求)
     - [4.2通过容器发送请求](#通过容器发送请求)
     - [4.3并行发送多个请求](#并行发送多个请求)

<!-- /TOC -->

## 容器

> 创建如下`app\model\User`类

```php
<?php
namespace app\model;

/**
 * Class User
 * @datetime 2020/7/2 11:23 PM
 * @author roach
 * @email jhq0113@163.com
 */
class User
{
    /**
     * @var string
     * @datetime 2020/7/2 11:22 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public $userName;

    /**
     * @var string
     * @datetime 2020/7/2 11:22 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public $password;

    /**
     * @var int
     * @datetime 2020/7/2 11:24 PM
     * @author roach
     * @email jhq0113@163.com
     */
    protected $_currentTime;

    /**
     * User constructor.
     * @param string $userName
     */
    public function __construct($userName = '')
    {
        $this->userName = $userName;
    }

     /**
     * @datetime 2020/7/6 10:49 上午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function init()
    {
        $this->_currentTime = time();
    }

    /**
     * @param int $time
     * @datetime 2020/7/3 1:40 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function setTime($time)
    {
        $this->_currentTime = $time;
    }

    /**
     * @return int
     * @datetime 2020/7/2 11:24 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public function getCurrentTime()
    {
        return $this->_currentTime;
    }
}
```

### 依赖注入

> `Container`通过`createRoach`实现依赖注入，`createRoach`方法每次运行都会根据配置创建一个全新的对象。

* 属性注入

```php
<?php
/**
 * @var app\model\User $user
 */
$user = Container::createRoach([
    'class' => 'app\model\User',
    'userName' => 'lao zhou',
    'password' => hash('sha1', '123456')
]);

echo json_encode([
    'userName'    => $user->userName,
    'password'    => $user->password,
], JSON_UNESCAPED_UNICODE).PHP_EOL;
```

> 以上例程输出

```json
{"userName":"lao zhou", "password":"7c4a8d09ca3762af61e59520943dc26494f8941b"}
```

* 通过`calls`配置进行构造函数注入

```php
<?php
/**
 * @var app\model\User $user
 */
$user = Container::createRoach([
    'class' => 'app\model\User',
    'calls' => [
        '__construct' => ['xiao mage']
    ],
]);

exit(json_encode([
    'userName'    => $user->userName,
    'password'    => $user->password,
], JSON_UNESCAPED_UNICODE).PHP_EOL);
```

> 以上例程输出

```json
{"userName":"xiao mage", "password":null}
```

* 通过`calls`进行方法注入

> 无参数方法注入

```php
<?php
/**
 * @var \app\model\User $user2
 */
$user2 = Container::createRoach([
    'class' => 'app\model\User',
    'calls' => [
        'init'
    ],
]);

echo json_encode([
    'currentTime'    => $user2->getCurrentTime()
], JSON_UNESCAPED_UNICODE).PHP_EOL;
```

> 以上例程会调用一次`init`方法，运行输出

```json
{"currentTime":1593735048}
```


> 有参数方法注入

```php
<?php
/**
 * @var \app\model\User $user2
 */
$user2 = Container::createRoach([
    'class' => 'app\model\User',
    'calls' => [
        '__construct' => ['boss zhou'],
        'setTime'     => [ time() ],
    ],
]);

echo json_encode([
    'userName'    => $user2->userName,
    'currentTime'    => $user2->getCurrentTime(),
], JSON_UNESCAPED_UNICODE).PHP_EOL;
```

> 以上例程输出

```json
{"userName":"boss zhou", "currentTime":1593755048}
```

> 调用队列

```php
<?php
/**
 * @var \app\model\User $user3
 */
$user3 = Container::createRoach([
    'class' => 'app\model\User',
    'calls' => [
        '__construct' => ['boss zhou'],
        [
            'method' => 'setTime',
            'params' => [ time() ],
        ],
        [
            'method' => 'setTime',
            'params' => [ time() ],
        ],
    ],
]);

echo json_encode([
    'userName'       => $user3->userName,
    'currentTime'    => $user3->getCurrentTime(),
], JSON_UNESCAPED_UNICODE).PHP_EOL;
```

> 以上例程，会先调用构造函数，然后调用两次`setTime`方法，以上例程输出

```json
{"userName":"boss zhou", "currentTime":1593756048}
```

[回到目录](#目录)

### 依赖注入容器

> 当向容器中放入一个包含`class`节点的数组时，容器会在调用`get`方法时根据数组配置实例化对象。

```php
<?php
//将app\model\User放入容器，app\model\User对象并未创建
Container::set('user', [
    'class' => 'app\model\User',
    'userName' => 'platform',
    'password' => hash_hmac('md5', 'roach', uniqid()),
    'calls'    => [
        'setTime' => [ time() ]
    ]
]);

/**
 * @var User $singleUser
 */
$singleUser = Container::get('user');
echo json_encode([
        'userName'    => $singleUser->userName,
        'password'    => $singleUser->password,
        'currentTime' => $singleUser->getCurrentTime()
    ], JSON_UNESCAPED_UNICODE).PHP_EOL;

$singleUser->userName = 'single';

/**
 * @var User $reGetUser
 */
$reGetUser = Container::get('user');
echo json_encode([
        'userName'    => $reGetUser->userName,
        'password'    => $reGetUser->password,
        'currentTime' => $reGetUser->getCurrentTime()
    ], JSON_UNESCAPED_UNICODE).PHP_EOL;
```

> 以上例程输出

```text
{"userName":"platform","password":"f66d715da660911d2d618cb36c24d30b","currentTime":1593755260}
{"userName":"single","password":"f66d715da660911d2d618cb36c24d30b","currentTime":1593755260}
```

* 1.依赖注入容器的对象是懒加载的，只有在调用`get`方法的时候才会真正的创建对象
* 2.依赖注入容器创建的对象是单例

[回到目录](#目录)

### 变量容器

> 当容器中放入一个非包含`class`节点数组的任意其他值时，容器只是一个存储变量的容器，且只有一份。

```php
<?php
Container::set('startTime', time());
Container::set('config', [
   'appName' => 'roach',
   'version' => '1.0.0'
]);
Container::set('user1', new User());

echo Container::get('startTime').PHP_EOL;
echo json_encode(Container::get('config'), JSON_UNESCAPED_UNICODE).PHP_EOL;
echo json_encode(Container::get('user1'), JSON_UNESCAPED_UNICODE).PHP_EOL;
```

> 以上例程输出

```text
1593705970
{"appName":"roach","version":"1.0.0"}
{"userName":null,"password":null}
```

[回到目录](#目录)

## 通用异常错误处理

> 使用`roach\exceptions\ErrorHandler`处理通用异常与错误

```php
<?php
use roach\Container;

//注入异常处理handler
Container::set('errorHandler', [
    'class' => 'roach\exceptions\ErrorHandler',
    //当捕获未被try...catch捕获的异常，会交由此handler处理
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

//出发调用未定义方法错误
fun();
//$a =5/0;
```

> 以上例程输出

```json
{"code":0,
"file":"...\/roach\/src\/test\/exception.php",
"line":25,
"message":"Call to undefined function fun()"}
```

[回到目录](#目录)

## 使用事件

> 事件是通过`roach\events\Event`实现的，是个`trait`类型，这样任何一个类只要`use`了该`trait`都可以支持事件机制。

* 1.为对象绑定事件，使用`on`方法，`on`方法第二个参数是个`callable`类型，该`callable`会默认附带一个参数，参数类型为`roach\events\EventObject`

* 2.触发对象事件，使用`trigger`方法触发事件，如果对象没有绑定事件，事件不会触发。

> 使用案例

```php
<?php
use roach\Container;
use roach\extensions\ECli;

require __DIR__.'/bootstrap.php';

/**
 * Class LoginServer
 * @datetime 2020/7/3 2:13 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class Login
{
    /**
     * 使用事件特性
     */
    use \roach\events\Event;

    const EVENT_LOGIN_BEFORE   = 'before';
    const EVENT_LOGIN_SUCCESS  = 'success';
    const EVENT_LOGIN_FAILED   = 'failed';

    /**
     * @param int    $phone
     * @param string $code
     * @return bool
     * @datetime 2020/7/3 2:12 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function loginByPhone($phone, $code)
    {
        $this->trigger(self::EVENT_LOGIN_BEFORE);

        if($phone == 13000000000 && $code === '123456') {

            $afterLoginEvent = Container::createRoach([
                'class' => 'roach\events\EventObject',
                'sender' => $this,
                'data'   => [
                    'userId' => time()
                ]
            ]);

            $this->trigger(self::EVENT_LOGIN_SUCCESS, $afterLoginEvent);
            return true;
        }

        $this->trigger(self::EVENT_LOGIN_FAILED);

        return false;
    }
}

/**
 * @var Login $login
 */
$login = Container::createRoach([
    'class' => Login::class
]);

$login->on(Login::EVENT_LOGIN_BEFORE, function (\roach\events\EventObject $event) {
    ECli::warn('start login');
});

$login->on(Login::EVENT_LOGIN_FAILED, function (\roach\events\EventObject $event) {
    ECli::error('login failed');
});

$login->on(Login::EVENT_LOGIN_SUCCESS, function (\roach\events\EventObject $event) {
    ECli::info('login success:'.$event->data['userId']);
});

$login->loginByPhone(1233333, '343434');
$login->loginByPhone(13000000000, '123456');
```
> 以上例程输出

```text
[2020-07-03 06:23:55]   warn:[ start login ]
[2020-07-03 06:23:55]   error:[ login failed ]
[2020-07-03 06:23:55]   warn:[ start login ]
[2020-07-03 06:23:55]   info:[ login success:1593757435 ]
```

[回到目录](#目录)

## 发送HTTP请求

> 发送HTTP请求需要通过`roach\http\Request`类去发送，该类会自动识别`http`与`https`协议请求。

### 连贯操作发送请求

```php
<?php
$res = (new Request())
        ->setUrl('https://www.baidu.com')
        ->setTimeout(3)
        ->setParams([
            'userName' => 'sdfafd'
        ])
        ->post();
\roach\extensions\ECli::error($res->getBody());
```

> 以上例程输出

```text
[2020-07-03 06:44:44]   error:[ <html>
<head><title>302 Found</title></head>
<body bgcolor="white">
<center><h1>302 Found</h1></center>
<hr><center>nginx</center>
</body>
</html>
 ]
```

[回到目录](#目录)

### 通过容器发送请求

```php
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
```

> 以上例程输出

```text
[2020-07-03 06:44:44]   info:[ <!DOCTYPE html><!--STATUS OK-->
<html>
<head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <link rel="dns-prefetch" href="//s1.bdstatic.com"/>
...
```

[回到目录](#目录)

### 并行发送多个请求

> 并行发送多个请求可以通过调用`roach\http\Request`的静态方法`multiRequest`实现，参数类型为`roach\http\Request`，参数个数是动态的，`multiRequest`方法会按照参数传入的顺序返回请求结果。

```php
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
```

> 以上例程输出

```text
[2020-07-03 07:00:14]   info:[ https://www.360.cn ]
[2020-07-03 07:00:14]   info:[ http://www.sina.com?from=1593759614 ]
[2020-07-03 07:00:14]   info:[ https://www.baidu.com?from=1593759614 ]
```

[回到目录](#目录)