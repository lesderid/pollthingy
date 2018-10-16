<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollVotingCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poll_voting_codes', function (Blueprint $table) {
            $table->char('id', 32);
            $table->boolean('used')->default(false);
            $table->char('poll_id', 6);

            $table->primary('id');
            $table->foreign('poll_id')->references('id')->on('polls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign(['poll_id']);

        Schema::dropIfExists('poll_voting_codes');
    }
}
