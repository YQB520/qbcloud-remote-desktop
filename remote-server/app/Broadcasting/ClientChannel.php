<?php

namespace App\Broadcasting;

use App\Models\Client;
use App\Models\User;

class ClientChannel
{
    public function __construct()
    {
        //
    }

    public function join(User $user, Client $client)
    {
        return [
            'id' => $user->id,
            'client_id' => $client->id,
            'connect' => (bool)request()->header('app'),
            'nickname' => $user->nickname
        ];
    }
}
