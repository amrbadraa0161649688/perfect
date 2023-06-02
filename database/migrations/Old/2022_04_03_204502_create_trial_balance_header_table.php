<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrialBalanceHeaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trial_balance_header', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('level');
            $table->boolean('is_zero');
            $table->unsignedBigInteger('company_group_id');
            $table->unsignedBigInteger('company_id');
            $table->date('from_date');
            $table->date('to_date');
            $table->float('total_opening_balance_debit')->default(0);
            $table->float('total_opening_balance_credit')->default(0);
            $table->float('total_trans_balance_debit')->default(0);
            $table->float('total_trans_balance_credit')->default(0);
            $table->float('total_balance_debit')->default(0);
            $table->float('total_balance_credit')->default(0);

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
        Schema::dropIfExists('trial_balance_header');
    }
}
