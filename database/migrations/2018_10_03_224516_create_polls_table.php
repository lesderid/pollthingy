<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->char('id', 6);
            $table->string('question');
            $table->enum('duplicate_vote_checking', ['none', 'cookies', 'codes']);
            $table->boolean('allow_multiple_answers');
            $table->timestamp('created_at');
            $table->timestamp('closes_at')->nullable();
            $table->string('admin_password')->nullable();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('polls');
    }
}
