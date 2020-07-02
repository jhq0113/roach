# roach

> roach是一个基于composer开发的一个基础工具库，可以嵌入到任何php语言开发的项目当中。

> roach使用简单，精简。

# 安装方式

```bash
composer require jhq0113/roach
```

<!-- TOC -->
# 目录

- [1.容器](#容器) 
     - [1.1创建对象](#创建对象)
     - [1.2依赖注入容器](#依赖注入容器)
     - [1.3变量容器](#变量容器)
    
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
     * @datetime 2020/7/2 11:24 PM
     * @author roach
     * @email jhq0113@163.com
     */
    public function init()
    {
        $this->_currentTime = time();
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

### 创建对象

> 使用`Container`创建以上`app\model\User`类

```php
<?php
/**
 * @var app\model\User $user
 */
$user = roach\Container::createRoach([
    'class' => 'app\model\User',
    'userName' => 'xiao mage',
    'password' => hash_hmac('md5', uniqid(), uniqid()),
]);

exit(json_encode([
    'userName'    => $user->userName,
    'password'    => $user->password,
    'currentTime' => $user->getCurrentTime()
], JSON_UNESCAPED_UNICODE).PHP_EOL);
```

> 以上例程输出

```json
{
  "userName":"xiao mage",
  "password":"c712ae92599499f72caf4cfe335085f3",
  "currentTime":1593703883
}
```

* 当类实现了`init`方法，`createRoach`方法在实例化对象之后会自动调用`init`方法。

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
{"userName":"platform","password":"f9c4fe32aa4bc45a4e0f136dc1d81fe3","currentTime":1593705480}
{"userName":"single","password":"f9c4fe32aa4bc45a4e0f136dc1d81fe3","currentTime":1593705480}
```

* 1.依赖注入容器的对象是懒加载的，只有在调用`get`方法的时候才会真正的创建对象，创建的对象如果实现了`init`方法，也会自动调用`init`方法
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