<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poll_votes', function (Blueprint $table) {
            $table->char('poll_id', 6);
            $table->unsignedInteger('poll_option_id');

            $table->foreign('poll_id')->references('id')->on('polls');
            $table->foreign('poll_option_id')->references('id')->on('poll_options');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign(['poll_id', 'poll_option_id']);

        Schema::dropIfExists('poll_votes');
    }
}
