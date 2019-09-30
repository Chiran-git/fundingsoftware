<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAggregationFieldsToDonorsAndCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->integer('total_donation_count')->nullable()->after('email');
            $table->bigInteger('total_donation_amount')->nullable()->after('total_donation_count');
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->bigInteger('total_donations')->nullable()->after('funds_raised');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn([
                'total_donation_count',
                'total_donation_amount',
            ]);
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn([
                'total_donations',
            ]);
        });
    }
}
