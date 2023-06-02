<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrialBalanceDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trial_balance_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trial_balance_header_id');
            $table->integer('account_id');
            $table->text('account_name');
            $table->text('code');
            $table->text('nature');
            $table->integer('level');
            $table->integer('main_type_id');
            $table->float('opening_balance_debit')->default(0);
            $table->float('opening_balance_credit')->default(0);
            $table->float('opening_balance_sign')->default(0);
            $table->float('trans_debit')->default(0);
            $table->float('trans_credit')->default(0);
            $table->float('trans_balance_debit')->default(0);
            $table->float('trans_balance_credit')->default(0);
            $table->float('trans_balance_sign')->default(0);
            $table->float('balance_debit')->default(0);
            $table->float('balance_credit')->default(0);
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
        Schema::dropIfExists('trial_balance_detail');
    }
}
