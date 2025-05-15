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
            //
           $table->string('ref_name')->after('finances_master_id')->nullable();
           $table->string('ref_mobile_no')->after('finances_master_id')->nullable();
            $table->string('ref_city')->after('finances_master_id')->nullable();
           $table->string('file_no')->after('ref_city')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finances', function (Blueprint $table) {
            //
            $table->dropColumn('ref_name');
            $table->dropColumn('ref_no');
            $table->dropColumn('file_no');
        });
    }
};
