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
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique()->nullable();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('customer_id');
            $table->decimal('price', 10, 2);
            $table->decimal('downpayment', 10, 2)->nullable();
            $table->decimal('processing_fee', 10, 2)->nullable();
            $table->decimal('emi_charger', 10, 2)->nullable();
            $table->decimal('finance_amount', 10, 2);
            $table->integer('month_duration');
            $table->decimal('emi_value', 10, 2);
            $table->decimal('penalty', 10, 2)->nullable();
            $table->text('dedication_date')->nullable();
            $table->year('finance_year');
            $table->enum('status', ['completed', 'pending', 'paid'])->default('pending');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};
