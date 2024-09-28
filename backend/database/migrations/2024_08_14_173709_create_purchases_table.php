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
            $table->integer('quantity')->unsigned();
            $table->decimal('total_price', 8, 2);
            $table->timestamp('purchase_date')->useCurrent();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_detail_id')->constrained('product_details')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
