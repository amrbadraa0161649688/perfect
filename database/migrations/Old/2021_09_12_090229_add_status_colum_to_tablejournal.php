<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumToTablejournal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->string('entry_status_id')->default(3);
            $table->double('debit',8,5)->default(0);
            $table->double('credit',8,5)->default(0);
            $table->double('balance',8,5)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->dropColumn('entry_status_id');
            $table->dropColumn('debit');
            $table->dropColumn('credit');
            $table->dropColumn('balance');
        });
    }
}
