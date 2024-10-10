<?php

namespace App\Models;

class Client extends BaseModel
{
    public $incrementing = false;

    protected $fillable = ['id', 'uid', 'pc_name', 'max_address', 'password', 'ip_address', 'note'];

}
