<?php
declare (strict_types=1);

namespace jolalau\WebManRedis;

/**
 * Redis缓存
 * @method static config(array $options)
 * @method static has(string $name)
 * @method static get(string $name, mixed $default = null)
 * @method static set(string $name, mixed $data, $expire = null)
 * @method static delete(string $name)
 * @method static clear()
 */
class Redis
{
    public static $instance = null;

    public static function instance()
    {
        if (!static::$instance) {
            static::$instance = new Manager();
        }
        return static::$instance;
    }

    public static function __callStatic($method, $arguments)
    {
        return static::instance()->{$method}(... $arguments);
    }

}