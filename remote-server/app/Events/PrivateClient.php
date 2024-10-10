<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class PrivateClient implements ShouldBroadcast
{
    /**
     * 全局安装 WebSocker 服务  npm install -g laravel-echo-server
     * 启动 队列 php artisan queue:work --queue=private
     * 启动 WebSocker 服务  laravel-echo-server start
     */
    use SerializesModels;

    public $broadcastQueue = 'private';

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
        return 'event.private';
    }

    public function broadcastOn()
    {
        // 私有频道
        return new PrivateChannel('client.private.' . $this->data['id']);
    }
}
