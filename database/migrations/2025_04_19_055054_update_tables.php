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
        Schema::dropIfExists('sales_transactions');

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('invoice_id')->nullable()->after('id')->constrained('sales')->onDelete('cascade');
            $table->string('payment_mode')->nullable()->after('invoice_id');
            $table->string('reference_no')->nullable()->after('amount');
            // example modification
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the old_table (if needed)
        Schema::create('sales_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('sales')->onDelete('cascade');
            $table->string('reference_no')->nullable();
            $table->string('amount')->nullable();
            $table->string('payment_mode')->nullable();
            $table->timestamps();
        });

        // Drop the columns added to transctions table
        Schema::table('transctions', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->dropColumn(['invoice_id', 'payment_mode', 'reference_no']);
        });
    }
};
