<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // GROUP_USER (group_id, user_id, created_at, subscribed_at, deleted_at)
        Schema::create('group_user', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id');
            $table->primary(['group_id', 'user_id']);
            $table->unsignedBigInteger('sponsor_id')->nullable(); // ! When subscribtion req come from another user. Will be FK NULLABLE
            $table->timestamp('subscribed_at')->nullable(); // Group owner accept user subsciption
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
        Schema::dropIfExists('group_user');
    }
}
