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
        Schema::table('sales_products', function (Blueprint $table) {
            $table->foreignId('imei_id')
                ->nullable()
                ->after('product_id')
                ->constrained('purchase_product')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_products', function (Blueprint $table) {
            $table->dropForeign(['imei_id']);
            $table->dropColumn('imei_id');
        });
    }
};
