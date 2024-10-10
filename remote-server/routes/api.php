<?php /** @noinspection PhpMethodParametersCountMismatchInspection */

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\IceServerController;
use App\Http\Controllers\Api\SocketController;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\UserController;
use Dingo\Api\Routing\Router;

$api = app(Router::class);

$api->version('v1', function ($api) {
    $api->group(['prefix' => 'test'], function ($api) {
        $api->get('/', [TestController::class, 'index'])->name('test');
    });
    $api->group(['prefix' => 'auth'], function ($api) {
        $api->post('/login', [AuthController::class, 'login'])->name('login');
        $api->post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
    $api->group(['middleware' => ['api.auth']], function ($api) {
        $api->get('/ice', [IceServerController::class, 'index'])->name('GetIceServer');
        $api->get('/online/{id}', [ClientController::class, 'online'])->name('OnlineClient');
        $api->group(['prefix' => 'socket'], function ($api) {
            $api->post('/', [SocketController::class, 'connect'])->name('SocketConnect');
        });
        $api->group(['prefix' => 'user'], function ($api) {
            $api->get('/show', [UserController::class, 'show'])->name('ShowUser');
            $api->post('/password', [UserController::class, 'password'])->name('EditPassword');
        });
        $api->group(['prefix' => 'client'], function ($api) {
            $api->get('/{id}', [ClientController::class, 'show'])->name('ShowClient');
            $api->get('/', [ClientController::class, 'index'])->name('GetClient');
            $api->post('/{id}', [ClientController::class, 'password'])->name('PassClient');
            $api->post('/', [ClientController::class, 'store'])->name('AddClient');
            $api->delete('/{id}', [ClientController::class, 'destroy'])->name('DelClient');
        });
    });
});
