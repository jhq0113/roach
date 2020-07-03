<?php
/**
 * Created by PhpStorm.
 * User: Jiang Haiqiang
 * Date: 2020/7/2
 * Time: 11:21 PM
 */
namespace app\model;
use roach\Container;

require __DIR__.'/bootstrap.php';

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

//-----------------属性注入--------------
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

//----------------构造方法注入----------------
/**
 * @var app\model\User $user1
 */
$user1 = Container::createRoach([
    'class' => 'app\model\User',
    'calls' => [
        '__construct' => ['xiao mage', hash_hmac('md5', uniqid(), uniqid())]
    ],
]);

echo json_encode([
        'userName'    => $user1->userName,
        'password'    => $user1->password,
], JSON_UNESCAPED_UNICODE).PHP_EOL;

//-------------方法注入-------------
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

//-------------------放入容器--------------
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

