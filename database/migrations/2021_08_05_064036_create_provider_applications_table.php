<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('category');
            $table->json('new_application')->nullable();
            $table->json('previous_application')->nullable();
            $table->json('validation')->nullable();
            $table->enum('status', ['Submitted', 'Resubmitted', 'Rejected', 'Approved']);
            $table->uuid('provider_id');
            $table->timestamps();

            $table->foreign('provider_id')->references('id')->on('providers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provider_applications');
    }
}
