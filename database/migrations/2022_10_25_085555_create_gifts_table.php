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
        Schema::create('gifts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('body');
            $table->string('main_image')->nullable();
            $table->json('images')->nullable();
            $table->foreignId('post_id')->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('gift_for')->constrained('users')->onDelete('cascade');
            $table->boolean('show_avatar')->default(1);
            $table->enum('privacy', ['private', 'public', 'friends'])->default('public');
            $table->json('access_list')->nullable();
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
        Schema::dropIfExists('gifts');
    }
};
