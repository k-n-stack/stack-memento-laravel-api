<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookmarkThreadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // BOOKMARK_THREAD (bookmark_id, thread_id)
        Schema::create('bookmark_thread', function (Blueprint $table) {
            $table->unsignedBigInteger('bookmark_id');
            $table->unsignedBigInteger('thread_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookmark_thread');
    }
}
