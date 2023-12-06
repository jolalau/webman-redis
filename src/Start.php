<?php

namespace jolalau\WebManRedis;

use Webman\Bootstrap;
use Workerman\Timer;

/**
 * 进程启动
 */
class Start implements Bootstrap
{
    public static function start($worker): void
    {
        $config = config('cache');
        if (!$config) {
            return;
        }
        $redis = new Manager();
        // 配置参数
        $redis->config($config);
        // 维持心跳
        if ($worker) {
            Timer::add(55, function ($redis) {
                $redis->get('ping');
            });
        }
    }
}