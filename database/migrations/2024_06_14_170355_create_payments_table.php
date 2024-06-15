<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('raz_id')->nullable();
            $table->integer('amount')->nullable();
            $table->string('status')->nullable();
            $table->string('pay_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('refused_status')->nullable();
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->string('payment_created_at')->nullable();
            $table->string('response')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
