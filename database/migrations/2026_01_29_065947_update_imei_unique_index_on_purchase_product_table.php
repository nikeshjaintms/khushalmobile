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
        Schema::table('purchase_product', function (Blueprint $table) {

            // 🔴 Drop old unique index (imei only)
            $table->dropUnique('purchase_product_imei_unique');

            // ✅ Add composite unique index (product_id + imei + deleted_at)
            $table->unique(
                ['product_id', 'imei', 'deleted_at'],
                'purchase_product_prod_imei_deleted_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('purchase_product', function (Blueprint $table) {

            // rollback composite index
            $table->dropUnique('purchase_product_prod_imei_deleted_unique');

            // restore original unique index
            $table->unique('imei', 'purchase_product_imei_unique');
        });
    }
};
