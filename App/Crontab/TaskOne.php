<?php

namespace App\Crontab;

use EasySwoole\EasySwoole\Crontab\AbstractCronTask;

class TaskOne extends AbstractCronTask {
    public static function getRule(): string {
        return '* * * * *';
    }

    public static function getTaskName(): string
    {
        return 'taskone';
    }

    public static function run(\swoole_server $server, int $taskId, int $fromWorkerId, $flags = null)
    {
        // var_dump($server, $taskId, $fromWorkerId, $flags);
        // var_dump('run task one', time());
    }
}