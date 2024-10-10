<?php

namespace App\Http\Controllers\Api;

use App\Events\PresenceClient;
use App\Events\PrivateClient;
use App\Http\Requests\ConnectRequest;

class SocketController extends ApiController
{
    public function connect(ConnectRequest $request)
    {
        $data = [
            'id' => $request->input('id'),
            'params' => now()->format('Y-m-d H:i:s')
        ];
        $res = broadcast(new PresenceClient($data));
        return $this->RD($res);
    }
}
