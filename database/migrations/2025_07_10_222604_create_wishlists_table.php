<?php
// filepath: c:\xampp\htdocs\custom-store\database\migrations\2024_XX_XX_XXXXXX_create_wishlists_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Zapobiegaj duplikatom - jeden użytkownik może dodać produkt tylko raz
            $table->unique(['user_id', 'product_id']);

            // Indeksy dla lepszej wydajności
            $table->index(['user_id', 'created_at']);
            $table->index('product_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wishlists');
    }
};
