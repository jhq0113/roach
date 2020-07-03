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