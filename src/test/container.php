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

/**
 * @var User $user
 */
$user = Container::createRoach([
    'class' => 'app\model\User',
    'userName' => 'xiao mage',
    'password' => hash_hmac('md5', uniqid(), uniqid()),
]);

echo json_encode([
        'userName'    => $user->userName,
        'password'    => $user->password,
        'currentTime' => $user->getCurrentTime()
    ], JSON_UNESCAPED_UNICODE).PHP_EOL;

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


Container::set('startTime', time());
Container::set('config', [
   'appName' => 'roach',
   'version' => '1.0.0'
]);
Container::set('user1', new User());

echo Container::get('startTime').PHP_EOL;
echo json_encode(Container::get('config'), JSON_UNESCAPED_UNICODE).PHP_EOL;
echo json_encode(Container::get('user1'), JSON_UNESCAPED_UNICODE).PHP_EOL;



