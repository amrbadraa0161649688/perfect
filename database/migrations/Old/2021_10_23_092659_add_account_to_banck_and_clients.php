<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountToBanckAndClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedInteger('supplier_account_id')->nullable();
            $table->unsignedInteger('customer_account_id')->nullable();
        });
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->unsignedInteger('account_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('supplier_account_id');
            $table->dropColumn('customer_account_id');
        });
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropColumn('account_id');
        });
    }
}
