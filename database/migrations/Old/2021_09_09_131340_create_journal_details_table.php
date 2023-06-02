<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('journal_entry_id');
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('cost_center_id')->nullable();
            $table->text('statement')->nullable();
            $table->double('debit',8,5)->default(0);
            $table->double('credit',8,5)->default(0);
            $table->double('balance',8,5)->default(0);
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
        Schema::dropIfExists('journal_details');
    }
}
