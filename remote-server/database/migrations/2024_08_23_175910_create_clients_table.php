<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->unsignedInteger('id')->unique()->comment('识别码');
            $table->char('uid', 36)->comment('用户UID');
            $table->string('pc_name', 100)->comment('设备名称');
            $table->ipAddress('max_address')->comment('MAC地址');
            $table->string('password', 255)->nullable()->comment('密码');
            $table->ipAddress('ip_address')->comment('内网IP');
            $table->string('note', 30)->nullable()->comment('备注');
            $table->timestamps();
            $table->primary(['pc_name', 'max_address']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
