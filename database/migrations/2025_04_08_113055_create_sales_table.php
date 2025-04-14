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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('invoice_no')->nullable();
            $table->date('invoice_date')->comment('Y-m-d format')->nullable();
            $table->double('sub_total')->nullable();
            $table->string('tax_type')->nullable();
            $table->double('total_tax_amount')->nullable();
            $table->double('total_amount')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sales_products',function(Blueprint $table){
            $table->id();
            $table->foreignId('sales_id')->constrained('sales')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->double('price')->nullable();
            $table->decimal('discount')->nullable();
            $table->decimal('discount_amount')->nullable();
            $table->decimal('price_subtotal')->nullable();
            $table->decimal('tax')->nullable();
            $table->double('tax_amount')->nullable();
            $table->double('price_total')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
        Schema::dropIfExists('sales_products');
    }
};
