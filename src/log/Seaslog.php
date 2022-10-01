<?php
/**
 * @description:seaslog
 * @author: jhq0113@163.com
 * @datetime: 2022/9/30 19:09
 */
namespace roach\log;

/**
 * @description:seaslog
 * @author: jhq0113@163.com
 * @datetime: 2022/9/30 19:12
 */
class Seaslog extends ILogger
{
    /**
     * @var string
     */
    public $basePath = '/tmp/logs';

    /**
     * @var string
     */
    public $module = 'jhq0113';

    /**
     * @param array $config
     * @throws \ReflectionException
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        ini_set('seaslog.level', $this->level);
    }

    /**
     * @author: jhq0113@163.com
     * @datetime: 2021/12/2 15:15
     */
    public function init()
    {
        \SeasLog::setBasePath($this->basePath);
        \SeasLog::setLogger($this->module);
    }

    /**
     * @param int $level
     * @param string $message
     * @param array $context
     * @return void|null
     * @author: jhq0113@163.com
     * @datetime: 2021/12/2 14:49
     */
    protected function _log($level, $message, array $context = array())
    {
        \SeasLog::log(self::$levelMap[ $level ], $message, $context);
    }
}