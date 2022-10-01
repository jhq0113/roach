<?php
/**
 * @description:redis扩展帮助类
 * @author: jhq0113@163.com
 * @datetime: 2022/10/1 22:07
 */
namespace roach\extensions;

/**
 * @description:redis扩展帮助类
 * @author: jhq0113@163.com
 * @datetime: 2022/10/1 22:07
 */
class ERedis extends IExtension
{
    const DEFAULT_TIMEOUT = 1;

    /**
     * 删除锁lua脚本
     */
    const UNLOCK_SCRIPT = <<<LUA
        if redis.call('get', KEYS[1]) == ARGV[1] then
            return redis.call('del', KEYS[1])
        end
        return 0
LUA;

    /**
     * 是否达到限速
     */
    const LIMIT_SCRIPT = <<<LUA
        local num = redis.call('incr', KEYS[1])
        if( num == 1 ) then
            return redis.call('expire', ARGV[2])
        end
        
        if( num > ARGV[1]) then
            return 1
        end
        return 0
LUA;


    /**
     * @var array
     */
    private static $_pool = [];

    /**
     * @param array $conf
     * @return \Redis
     * @author: jhq0113@163.com
     * @datetime: 2022/10/1 14:54
     */
    public static function redis($conf)
    {
        $key = "{$conf['host']}-{$conf['port']}";
        if(!is_null(self::$_pool[ $key ])) {
            return self::$_pool[ $key ];
        }

        $timeout = $conf['timeout'] ?? self::DEFAULT_TIMEOUT;
        $redis = new \Redis();
        $redis->connect($conf['host'], $conf['port'], $timeout);
        if($conf['auth']) {
            $redis->auth($conf['auth']);
        }

        $redis->select(0);

        self::$_pool[ $key ] = $redis;
        return self::$_pool[ $key ];
    }

    /**
     * @param \Redis  $redis
     * @param string $key
     * @param int    $timeout
     * @return bool|string
     * @datetime 2020/6/22 5:51 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function acquire(\Redis $redis, $key, $timeout = 8)
    {
        $token = uniqid().EString::createRandStr(5);
        $isLock = $redis->set($key, $token, ['NX', 'EX' => $timeout]);
        if(!$isLock) {
            return false;
        }

        return $token;
    }

    /**
     * @param \Redis  $redis
     * @param string $key
     * @param string $token
     * @return mixed
     * @datetime 2020/6/22 5:53 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function release(\Redis $redis, $key, $token)
    {
        $hash = $redis->script('load',self::UNLOCK_SCRIPT);
        return $redis->evalSha($hash, [$key, $token],1);
    }

    /**该限速方法能满足一定的限速，但是限速不均匀，如果需要均匀限速可以考虑令牌桶
     * @param \Redis $redis
     * @param string $key
     * @param int    $times       最大次数
     * @param int    $timeout     限制时长
     * @return bool
     * @datetime 2020/6/29 11:51 上午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static function isLimit(\Redis $redis, $key, $times, $timeout)
    {
        $hash = $redis->script('load', self::LIMIT_SCRIPT);
        return $redis->evalSha($hash, [ $key, $times, $timeout], 1);
    }
}