<?php

namespace App\Events;

use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class PresenceClient implements ShouldBroadcast
{
    /**
     * 全局安装 WebSocker 服务  npm install -g laravel-echo-server
     * 启动 队列 php artisan queue:work --queue=presence
     * 启动 WebSocker 服务  laravel-echo-server start
     * /api/auth/websocket
     */
    use SerializesModels;

    public $broadcastQueue = 'presence';

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
        return 'event.presence';
    }

    public function broadcastOn()
    {
        // Presence 频道
        return new PresenceChannel('client.presence.' . $this->data['id']);
    }
}
