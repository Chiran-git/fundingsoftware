<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDecimalAmountsToInteger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->bigInteger('fundraising_goal')->nullable()->change();
            $table->bigInteger('funds_raised')->nullable()->change();
        });

        Schema::table('campaign_rewards', function (Blueprint $table) {
            $table->bigInteger('min_amount')->nullable()->change();
        });

        Schema::table('payouts', function (Blueprint $table) {
            $table->bigInteger('deposit_amount')->nullable()->change();
            $table->bigInteger('gross_amount')->nullable()->change();
        });

        Schema::table('donations', function (Blueprint $table) {
            $table->bigInteger('gross_amount')->nullable()->change();
            $table->bigInteger('stripe_fee')->nullable()->change();
            $table->bigInteger('application_fee')->nullable()->change();
            $table->bigInteger('net_amount')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->decimal('fundraising_goal', 16,2)->nullable()->change();
            $table->decimal('funds_raised', 16,2)->nullable()->change();
        });

        Schema::table('campaign_rewards', function (Blueprint $table) {
            $table->decimal('min_amount', 16,2)->nullable()->change();
        });

        Schema::table('payouts', function (Blueprint $table) {
            $table->decimal('deposit_amount', 16,2)->nullable()->change();
            $table->decimal('gross_amount', 16,2)->nullable()->change();
        });

        Schema::table('donations', function (Blueprint $table) {
            $table->decimal('gross_amount', 16,2)->nullable()->change();
            $table->decimal('stripe_fee', 16,2)->nullable()->change();
            $table->decimal('application_fee', 16,2)->nullable()->change();
            $table->decimal('net_amount', 16,2)->nullable()->change();
        });
    }
}
