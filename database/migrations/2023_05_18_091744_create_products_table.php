<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->string('name')->nullable();
            $table->string('slug')->unique();
            $table->string('barcode')->nullable();
            $table->bigInteger('cost_price')->default(0);
            $table->bigInteger('selling_price')->default(0);
            $table->string('status')->nullable();
            $table->json('meta')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
