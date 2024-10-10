<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SocketQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;


    protected $data;

    public function __construct(array $data)
    {
        // php artisan queue:work --queue=remote-socket-queue
        $this->data = $data;
    }

    public function handle()
    {
        // 需要干什么
    }
}
