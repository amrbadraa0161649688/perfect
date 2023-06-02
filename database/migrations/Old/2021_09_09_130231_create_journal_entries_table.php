<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
           // $table->unsignedInteger('subsidiary_id')->nullable();
            $table->unsignedInteger('branch_id')->nullable();

            $table->text('user_statement')->nullable();
            $table->text('general_statement')->nullable();

            $table->unsignedInteger('accounting_entry_id')->nullable();

            $table->string('journal_entry_no')->nullable();
            $table->string('doc_no')->nullable();
            $table->string('file_no')->nullable();

            $table->unsignedInteger('account_period_id');
            $table->date('date');
            $table->unsignedInteger('created_by')->nullable();
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
        Schema::dropIfExists('journal_entries');
    }
}
