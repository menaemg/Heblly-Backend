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
        Schema::table('wishlist', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade');


            $table->dropForeign(['post_id']);
            $table->foreign('post_id')
            ->references('id')->on('posts')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wishlist', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')
            ->references('id')->on('users');
            $table->dropForeign(['post_id']);
            $table->foreign('post_id')
            ->references('id')->on('posts');
        });
    }
};
