<?php

namespace App\Http\Controllers\Api;

class IceServerController extends ApiController
{
    public function index()
    {
        // Cloudflare  Turn服务
        $turnId = 'xxxxxx';
        $turnToken = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
        $client = new \GuzzleHttp\Client();
        $options = [
            'verify' => false,
            'headers' => ['Content-Type' => 'application/json', 'Authorization' => "Bearer $turnToken"],
            'json' => ['ttl' => 86400] // 秒 过期时间 1天
        ];
        $response = $client->request('POST', "https://rtc.live.cloudflare.com/v1/turn/keys/$turnId/credentials/generate", $options);
        $status = $response->getStatusCode();
        if ($status >= 200 && $status < 300) {
            $content = json_decode($response->getBody()->getContents(), true);
            return $this->RD($content);
        }
        return $this->RD(false);
    }
}
