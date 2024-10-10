<?php

namespace App\Http\Transformers;

use App\Models\Client;
use Illuminate\Contracts\Encryption\DecryptException;

class ClientTransformer extends BaseTransformer
{
    public function transform(Client $client)
    {
        try {
            $password = decrypt($client->password);
        } catch (DecryptException $e) {
            $password = null;
        }
        return [
            'id' => (string)$client->id,
            'pc_name' => $client->pc_name,
            'password' => $password,
            'ip_address' => $client->ip_address,
            'note' => $client->note
        ];
    }
}
