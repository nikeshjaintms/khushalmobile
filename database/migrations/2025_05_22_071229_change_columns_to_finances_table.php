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
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('emi_value', 10, 2)->nullable();
            $table->decimal('finance_amount', 10, 2)->nullable()->change();
            $table->year('finance_year')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finances', function (Blueprint $table) {
           $table->dropColumn('price','emi_value','finance_year','finance_amount');
        });
    }
};
