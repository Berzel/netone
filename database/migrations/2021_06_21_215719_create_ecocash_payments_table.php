<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcocashPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecocash_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topup_id')->nullable();
            $table->unsignedInteger('amount');
            $table->string('ecocash_number');
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ecocash_payments');
    }
}
