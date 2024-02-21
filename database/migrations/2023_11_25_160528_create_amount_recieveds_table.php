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
        Schema::create('amount_recieveds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("buyer_purchaser_id");
            $table->string("head");
            $table->string("qty");
            $table->string("amount");
            $table->string("total_amount");
            $table->string("status");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amount_recieveds');
    }
};
