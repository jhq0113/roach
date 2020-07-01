<?php
namespace roach;

/**
 * Class Roach
 * @package roach
 * @datetime 2020/7/1 4:50 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class Roach
{
    /**
     * Roach constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        foreach ($config as $property => $value) {
            $this->$property = Container::insure($value);
        }
    }
}