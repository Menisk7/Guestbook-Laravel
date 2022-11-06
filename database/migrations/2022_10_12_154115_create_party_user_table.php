<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('party_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('party_id')->unsigned();
            $table->integer('user_id')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('party_user');
    }
};
