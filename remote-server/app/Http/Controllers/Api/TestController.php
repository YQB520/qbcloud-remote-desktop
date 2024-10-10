<?php

namespace App\Http\Controllers\Api;

use App\Events\CommonEvent;
use App\Events\PresenceClient;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index()
    {
        $data = [
            'id' => '794414902',
            'params' => now()->format('Y-m-d H:i:s')
        ];
        $res = broadcast(new PresenceClient($data));
        dd($res);
    }

}
