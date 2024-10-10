<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ClientRequest;
use App\Http\Transformers\ClientTransformer;
use App\Models\Client;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;

class ClientController extends ApiController
{
    public function index()
    {
        $data = Client::select('id', 'pc_name', 'password', 'ip_address', 'note')
            ->paginate(request('size'));
        return $this->PD($data, new ClientTransformer());
    }

    public function store(ClientRequest $request)
    {
        // encrypt
        $data = [
            'uid' => 'f3f509cf-52bd-4ac9-81f9-f3a8a7e441ec',
            'pc_name' => $request->input('pc_name'),
            'max_address' => $request->input('max_address'),
            'ip_address' => $request->input('ip_address')
        ];
        $where = [['pc_name', '=', $data['pc_name']], ['max_address', '=', $data['max_address']]];
        $exists = Client::select('id')->where($where)->exists();
        if ($exists) {
            $self = Client::select('id', 'password')->where($where)->first();
            try {
                $password = decrypt($self->password);
            } catch (DecryptException $e) {
                $password = null;
            }
            return $this->RD(['id' => (string)$self->id, 'password' => $password]);
        }
        $data['id'] = mt_rand(123456789, 987654321);
        Client::create($data);
        return $this->RD(['id' => (string)$data['id'], 'password' => null]);
    }

    public function password(int $id)
    {
        $password = request('password') ? encrypt(request('password')) : null;
        Client::where('id', $id)->update(['password' => $password]);
        return $this->RD(200);
    }

    public function destroy(int $id)
    {
        Client::destroy($id);
        return $this->DT();
    }

    public function show(int $id)
    {
        $data = Client::find($id, ['id', 'pc_name', 'password', 'ip_address', 'note']);
        return $this->item($data, new ClientTransformer());
    }

    public function online(int $id)
    {
        $sockets = Redis::get("presence-client.presence.$id:members");
        $sockets = json_decode($sockets, true);
        $client = Arr::first($sockets, function ($value) {
            return $value['user_info'] && $value['user_info']['connect'] === true;
        }, false);
        return $this->RD($client);
    }
}
