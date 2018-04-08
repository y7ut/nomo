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
            $table->string('avatar');
            $table->string('intro')->nullable();
            $table->string('url')->nullable();
            $table->string('confirmation_token');
            $table->text('comment_tail')->nullable();
            $table->integer('integration')->default(0);
            $table->integer('posts_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('attendpost_count')->default(0);
            $table->integer('attendboard_count')->default(0);
            $table->integer('followed_count')->default(0);
            $table->integer('follower_count')->default(0);
            $table->integer('signin_count')->default(0);
            $table->timestamp('lastsignin')->nullable();
            $table->smallInteger('is_active')->default(0);
            $table->json('settings')->nullable();
            $table->rememberToken();
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
