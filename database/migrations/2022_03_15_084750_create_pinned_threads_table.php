<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinnedThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // PINNED_THREAD (user_id, thread_id, created_at, deleted_at)
        Schema::create('pinned_threads', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('thread_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pinned_threads');
    }
}
