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
        Schema::create('recieved_supplier_amounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("supplier_id");
            $table->string("amount");
            $table->string("remarks")->nullable();
            $table->string("amount_status")->default("Out");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recieved_supplier_amounts');
    }
};
