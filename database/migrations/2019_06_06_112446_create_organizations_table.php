<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('owner_id')->unsigned();
            $table->string('name');
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->bigInteger('country_id')->unsigned()->nullable();
            $table->string('phone')->nullable();
            $table->bigInteger('currency_id')->unsigned()->nullable();
            $table->string('slug')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('cover_image_filename')->nullable();
            $table->bigInteger('cover_image_filesize')->unsigned()->nullable();
            $table->string('logo')->nullable();
            $table->string('logo_filename')->nullable();
            $table->bigInteger('logo_filesize')->unsigned()->nullable();
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->string('appeal_headline')->nullable();
            $table->text('appeal_message')->nullable();
            $table->string('appeal_photo')->nullable();
            $table->string('appeal_photo_filename')->nullable();
            $table->bigInteger('appeal_photo_filesize')->unsigned()->nullable();
            $table->text('system_donor_questions')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // User roles for organizations will be managed by spatie/laravel-permissions package
        Schema::create('organization_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('organization_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('organization_connected_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('organization_id')->unsigned();
            $table->bigInteger('created_by_id')->unsigned();
            $table->boolean('is_default')->default(1);
            $table->string('nickname')->nullable();
            $table->string('stripe_user_id')->nullable();
            $table->text('stripe_access_token')->nullable();
            $table->boolean('stripe_livemode')->nullable();
            $table->text('stripe_refresh_token')->nullable();
            $table->string('stripe_token_type')->nullable();
            $table->string('stripe_publishable_key')->nullable();
            $table->string('stripe_scope')->nullable();
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
        Schema::dropIfExists('organizations');

        Schema::dropIfExists('organization_connected_accounts');
    }
}
