<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('organization_id')->unsigned();
            $table->bigInteger('created_by_id')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->decimal('fundraising_goal', 16,2)->nullable();
            $table->decimal('funds_raised', 16,2)->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('image')->nullable();
            $table->string('image_filename')->nullable();
            $table->bigInteger('image_filesize')->unsigned()->nullable();
            $table->string('video_url')->nullable();
            $table->text('description')->nullable();
            $table->text('donor_message')->nullable();
            $table->string('payout_method')->nullable();
            $table->bigInteger('payout_connected_account_id')->unsigned()->nullable();
            $table->string('payout_name')->nullable();
            $table->string('payout_address1')->nullable();
            $table->string('payout_address2')->nullable();
            $table->string('payout_city')->nullable();
            $table->string('payout_state')->nullable();
            $table->string('payout_zipcode')->nullable();
            $table->bigInteger('payout_country_id')->unsigned()->nullable();
            $table->string('payout_payable_to')->nullable();
            $table->string('payout_schedule')->nullable();
            $table->integer('sort_order')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->bigInteger('published_by_id')->unsigned()->nullable();
            $table->timestamp('disabled_at')->nullable();
            $table->bigInteger('disabled_by_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('campaign_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('organization_id')->unsigned();
            $table->bigInteger('campaign_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('campaign_rewards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('organization_id')->unsigned();
            $table->bigInteger('campaign_id')->unsigned();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('min_amount', 16,2);
            $table->integer('quantity');
            $table->integer('quantity_rewarded')->nullable();
            $table->string('image')->nullable();
            $table->string('image_filename')->nullable();
            $table->bigInteger('image_filesize')->nullable();
            $table->timestamp('disabled_at')->nullable();
            $table->bigInteger('disabled_by_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('payouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('organization_id')->unsigned();
            $table->bigInteger('campaign_id')->unsigned();
            $table->bigInteger('organization_connected_account_id')->unsigned()->nullable();
            // These payout_ fields will be populated if the payout is via check
            $table->string('payout_name')->nullable();
            $table->string('payout_address1')->nullable();
            $table->string('payout_address2')->nullable();
            $table->string('payout_city')->nullable();
            $table->string('payout_state')->nullable();
            $table->string('payout_zipcode')->nullable();
            $table->bigInteger('payout_country_id')->unsigned()->nullable();
            $table->string('payout_payable_to')->nullable();
            $table->timestamp('issue_date')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->decimal('deposit_amount', 16,2)->nullable();
            $table->decimal('gross_amount', 16,2)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('campaigns');

        Schema::dropIfExists('campaign_users');

        Schema::dropIfExists('campaign_rewards');

        Schema::dropIfExists('payouts');
    }
}
