<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('password', 100);
            $table->string('path', 120)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table("users")->insert([
            [
                "name" => "Jesús Daza",
                "password" => bcrypt("jesusjym"),
                "path" => "/images/user/default.svg",
            ],
            [
                "name" => "Macarena Romero",
                "password" => bcrypt("macajym"),
                "path" => "/images/user/default.svg",
            ],
        ]);
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
};
