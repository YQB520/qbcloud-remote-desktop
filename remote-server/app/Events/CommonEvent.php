<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class CommonEvent implements ShouldBroadcast
{
    /**
     * 全局安装 WebSocker 服务  npm install -g laravel-echo-server
     * 启动 队列   php artisan queue:work  or  php artisan queue:listen
     * 启动 WebSocker 服务  laravel-echo-server start
     */
    use SerializesModels;

    public $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function broadcastWith()
    {
        return $this->data;
    }

    public function broadcastAs()
    {
        return 'common.channel';
    }

    public function broadcastOn()
    {
        return new Channel('common'); // 公共频道
    }

}
