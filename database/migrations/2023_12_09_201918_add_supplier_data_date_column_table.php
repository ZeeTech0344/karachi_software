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
        Schema::table('supplier_data', function (Blueprint $table) {
            // $table->string("date");
            // $table->integer("status")->default(0);
            // $table->string("remarks")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_data', function (Blueprint $table) {
            // $table->string("date");
            // $table->integer("status")->default(0);
            // $table->string("remarks")->nullable();
        });
    }
};
