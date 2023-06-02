<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountPeriodRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_period_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('account_period_id');
            $table->unsignedInteger('currency_id');
            $table->decimal('rate',8,5);
            $table->timestamps();
        });
        Schema::table('periods', function (Blueprint $table) {
            $table->unsignedInteger('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_period_rates');

        Schema::table('periods', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
