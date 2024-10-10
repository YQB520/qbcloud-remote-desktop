<?php

namespace App\Http\Controllers\Web;

use App\Events\PrivateClient;
use App\Events\CommonEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IndexController extends Controller
{
    public function index()
    {

    }
}
