<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBankFieldsToConnectedAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_connected_accounts', function (Blueprint $table) {
            $table->string('external_account_object')->nullable()->after('nickname');
            $table->string('external_account_id')->nullable()->after('external_account_object');
            $table->string('external_account_name')->nullable()->after('external_account_id');
            $table->string('external_account_last4')->nullable()->after('external_account_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organization_connected_accounts', function (Blueprint $table) {
            $table->dropColumn([
                'external_account_object',
                'external_account_id',
                'external_account_name',
                'external_account_last4',
            ]);
        });
    }
}
