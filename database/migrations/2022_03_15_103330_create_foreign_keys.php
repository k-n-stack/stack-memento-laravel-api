<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('friends', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('friend_id')->references('id')->on('users');
        });
        Schema::table('threads', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::table('comments', function(Blueprint $table) {
            $table->foreign('poster_id')->references('id')->on('users');
            $table->foreign('parent_id')->nullable()->references('id')->on('comments');
            $table->foreign('bookmark_id')->references('id')->on('bookmarks');
        });
        Schema::table('groups', function(Blueprint $table) {
            $table->foreign('owner_id')->references('id')->on('users');
        });
        Schema::table('redirections', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('bookmark_id')->references('id')->on('bookmarks');
        });
        Schema::table('votes', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('bookmark_id')->references('id')->on('bookmarks');
        });
        Schema::table('searches', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::table('friend_thread', function(Blueprint $table) {
            $table->foreign('friend_id')->references('friend_id')->on('friends');
            $table->foreign('thread_id')->references('id')->on('threads');
        });
        Schema::table('bookmark_thread', function(Blueprint $table) {
            $table->foreign('bookmark_id')->references('id')->on('bookmarks');
            $table->foreign('thread_id')->references('id')->on('threads');
        });
        Schema::table('bookmark_tag', function(Blueprint $table) {
            $table->foreign('bookmark_id')->references('id')->on('bookmarks');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
        Schema::table('group_user', function(Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('sponsor_id')->nullable()->references('id')->on('users'); // !!!!
        });
        Schema::table('group_thread', function(Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('thread_id')->references('id')->on('threads');
        });
        Schema::table('pinned_threads', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('thread_id')->references('id')->on('threads');
        });
        Schema::table('revoked_group_owners', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('group_id')->references('id')->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('foreign_keys');
    }
}
