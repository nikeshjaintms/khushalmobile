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
        Schema::table('finances', function (Blueprint $table) {
            // $table->dropColumn('invoice_no');
            $table->foreignId('invoice_id')->after('id')->references('id')->on('sales')->onDelete('cascade');


            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finances', function (Blueprint $table) {
            // Drop the foreign key column
            $table->dropForeign(['invoice_id']);
            $table->dropColumn('invoice_id');

            // Re-add the old column
            $table->string('invoice_no')->nullable(); // or use the original type
        });
    }
};
