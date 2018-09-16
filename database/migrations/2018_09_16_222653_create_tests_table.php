<?php

use Illuminate\Database\Schema\Blueprint;
use \App\Database\Migration;

class CreatetestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('content');
            // Add some more columns

            $table->timestamps();
        });

        $this->updateTimestampDefaultValue('tests', ['updated_at'], ['created_at']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tests');
    }
}
