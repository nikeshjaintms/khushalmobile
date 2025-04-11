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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dealer_id')->nullable();
            $table->string('po_no')->nullable();
            $table->date('po_date')->nullable();
            $table->decimal('sub_total')->nullable();
            $table->enum('tax_type',['cgst/sgst','igst'])->nullable();
            $table->decimal('total_tax_amount')->nullable();
            $table->decimal('total')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('purchase_product', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->string('color');
            $table->string('imei')->nullable();
            $table->decimal('price')->nullable();
            $table->decimal('discount')->nullable();
            $table->decimal('discount_amount')->nullable();
            $table->decimal('price_subtotal')->nullable();
            $table->decimal('tax')->nullable();
            $table->decimal('tax_amount')->nullable();
            $table->decimal('product_total')->nullable();
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
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('purchase_product');
    }
};
