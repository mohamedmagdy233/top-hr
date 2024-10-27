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
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('name');
            $table->string('code');
            $table->string('phone');
            $table->string('password');
            $table->string('image')->default('avatar.png');
            $table->bigInteger('salary')->default(0);
            $table->integer('holidays')->default(0);
            $table->text('fcm_token')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamp('registered_at');

            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('group_id')->references('id')->on('groups')->cascadeOnDelete()->cascadeOnUpdate();
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
};
