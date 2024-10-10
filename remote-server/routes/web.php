<?php

use App\Http\Controllers\Web\IndexController;

Route::get('/', [IndexController::class, 'index']);
