<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;

class ApiController extends Controller
{
    use Helpers;

    protected function AT(): Response
    {
        return $this->response->array(['message' => '新增成功']);
    }

    protected function ET(): Response
    {
        return $this->response->array(['message' => '更新成功']);
    }

    protected function DT(): Response
    {
        return $this->response->array(['message' => '删除成功']);
    }

    protected function ST($status): Response
    {
        return $this->response->array(['message' => $status ? '启用成功' : '禁用成功']);
    }

    protected function ZT($message): Response
    {
        return $this->response->array(['message' => $message]);
    }

    protected function UT($data): Response
    {
        return $this->response->array(['message' => null, 'data' => $data]);
    }

    protected function RD($data): Response
    {
        return $this->response->array(['data' => $data]);
    }

    protected function error($message)
    {
        $this->response->error($message, 412);
    }

    protected function item($data, $transformer): Response
    {
        return $this->response->item($data, $transformer);
    }

    protected function collection($data, $transformer): Response
    {
        return $this->response->collection($data, $transformer);
    }

    protected function PD($data, $transformer): Response
    {
        return $this->response->paginator($data, $transformer);
    }
}
