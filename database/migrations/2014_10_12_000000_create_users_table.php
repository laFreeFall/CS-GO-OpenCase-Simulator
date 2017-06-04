<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('points')->default(0);
            $table->string('avatar')->nulalble();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert(
            [
                ['name' => 'chikaldirick', 'avatar' => 'chikaldirick.png', 'email' => 'chikaldirick@gmail.com', 'password' => '$2y$10$UXJOvBWhEZ1l1gTrd8dWGeaFQoC.ZEaDx05MfWRHv.j95C/A7DEVu', 'points' => '0', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()],
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
