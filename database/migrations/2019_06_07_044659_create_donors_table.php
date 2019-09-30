<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('last_name');
            $table->string('email');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('organization_id')->unsigned();
            $table->bigInteger('campaign_id')->unsigned();
            $table->bigInteger('donor_id')->unsigned();
            $table->bigInteger('currency_id')->unsigned();
            $table->decimal('gross_amount', 16,2)->nullable();
            $table->decimal('stripe_fee', 16,2)->nullable();
            $table->decimal('application_fee', 16,2)->nullable();
            $table->decimal('net_amount', 16,2)->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_charge_id')->nullable();
            $table->string('stripe_payment_status')->nullable();
            $table->string('card_name')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            // Where should the payout happen for this donation
            $table->string('payout_method')->nullable();
            $table->bigInteger('payout_connected_account_id')->unsigned()
                ->nullable();

            $table->string('mailing_address1')->nullable();
            $table->string('mailing_address2')->nullable();
            $table->string('mailing_city')->nullable();
            $table->string('mailing_state')->nullable();
            $table->string('mailing_zipcode')->nullable();
            $table->bigInteger('mailing_country_id')->unsigned()->nullable();
            $table->string('billing_address1')->nullable();
            $table->string('billing_address2')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_zipcode')->nullable();
            $table->bigInteger('billing_country_id')->unsigned()->nullable();
            $table->text('comments')->nullable();
            $table->bigInteger('payout_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('donor_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('organization_id')->unsigned();
            $table->string('question');
            $table->string('type')->nullable();
            $table->text('options')->nullable();
            $table->string('placeholder')->nullable();
            $table->boolean('is_required')->default(0);
            $table->integer('sort_order')->nullable();
            $table->string('size')->nullable();
            $table->timestamp('disabled_at')->nullable();
            $table->bigInteger('disabled_by_id')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('donation_question_answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('organization_id')->unsigned();
            $table->bigInteger('campaign_id')->unsigned();
            $table->bigInteger('donation_id')->unsigned();
            $table->bigInteger('donor_question_id')->unsigned();
            $table->text('answer')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('donation_rewards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('organization_id')->unsigned();
            $table->bigInteger('campaign_id')->unsigned();
            $table->bigInteger('donation_id')->unsigned();
            $table->bigInteger('campaign_reward_id')->unsigned();
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
        Schema::dropIfExists('donors');

        Schema::dropIfExists('donations');

        Schema::dropIfExists('donor_questions');

        Schema::dropIfExists('donation_question_answers');

        Schema::dropIfExists('donation_rewards');
    }
}
