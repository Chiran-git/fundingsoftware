<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStripeCurrencyFieldsToDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('stripe_fee_currency', 5)->nullable()->after('stripe_fee');
            $table->string('application_fee_currency', 5)->nullable()->after('application_fee');
            $table->boolean('live_mode')->nullable()->after('stripe_payment_status');
            $table->string('stripe_transaction_id', 100)->nullable()->after('stripe_charge_id');
            $table->string('stripe_account_id', 100)->nullable()->after('stripe_transaction_id');

            // We don't need stripe_customer_id in donations table
            $table->dropColumn('stripe_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_fee_currency',
                'application_fee_currency',
                'live_mode',
                'stripe_transaction_id',
                'stripe_account_id',
            ]);

            $table->string('stripe_customer_id')->nullable()->after('net_amount');
        });
    }
}
