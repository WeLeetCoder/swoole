<?php

namespace App\Crontab;

use EasySwoole\EasySwoole\Crontab\AbstractCronTask;

use App\Common\InstanceList;

class Check extends AbstractCronTask {
    public static function getRule(): string {
        return '* * * * *';
    }

    public static function getTaskName(): string
    {
        return 'taskone';
    }

    public static function run(\swoole_server $server, int $taskId, int $fromWorkerId, $flags = null)
    {
        
        // InstanceList::check();
    }
}