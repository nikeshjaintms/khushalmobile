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
        Schema::create('deductions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreignId('finance_id')->references('id')->on('finances')->onDelete('cascade');
            $table->decimal('emi_value', 10, 2)->nullable();
            $table->decimal('emi_value_paid', 10, 2)->nullable();
            $table->decimal('penalty',10,2)->nullable();
            $table->decimal('remaining',10,2)->nullable();
            $table->decimal('total',10,2)->nullable();
            $table->decimal('payment_mode')->nullable();
            $table->string('refernce_no')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deductions');
    }
};
