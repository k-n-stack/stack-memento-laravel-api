<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // SEARCH (id, user_id, search_string, category, search_at)
        Schema::create('searchs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('search_string', 128);
            $table->string('category', 64);
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
        Schema::dropIfExists('searchs');
    }
}
