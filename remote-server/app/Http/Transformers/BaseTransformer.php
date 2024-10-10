<?php

namespace App\Http\Transformers;

use Illuminate\Support\Carbon;
use League\Fractal\TransformerAbstract;

class BaseTransformer extends TransformerAbstract
{
    protected static function dealTime($time): string
    {
        return Carbon::parse($time)->format('Y-m-d H:i:s');
    }

}
