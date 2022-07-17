<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('full_name')->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('nric_number')->unique()->nullable();
            $table->string('nric_front')->nullable();
            $table->string('nric_back')->nullable();
            $table->string('nric_selfie')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_code');
            $table->string('phone_number');
            $table->string('password');
            $table->string('profile_picture')->nullable();
            $table->string('referral_code')->unique();
            $table->uuid('referred_by')->nullable();
            $table->string('file_secret_key')->unique();
            $table->enum('registration_status', ['Not Complete', 'Submitted', 'Resubmitted', 'Rejected', 'Approved'])->default('Not Complete');
            $table->timestamps();
        });

        Schema::table('providers', function($table) {
            $table->foreign('referred_by')->references('id')->on('providers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('providers');
    }
}
