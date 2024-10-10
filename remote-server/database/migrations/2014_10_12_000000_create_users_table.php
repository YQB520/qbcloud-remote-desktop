<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('uid', 36)->unique();
            $table->string('nickname', 20);
            $table->string('username', 30);
            $table->string('password', 60);
            $table->ipAddress('ip')->nullable();
            $table->boolean('status')->unsigned()->default(1);
            $table->boolean('errors')->unsigned()->default(0);
            $table->timestamp('login_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
