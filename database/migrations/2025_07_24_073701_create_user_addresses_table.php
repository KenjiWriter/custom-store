<?php
// filepath: database/migrations/2025_07_24_000000_create_user_addresses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Sprawdź czy tabela już istnieje
        if (!Schema::hasTable('user_addresses')) {
            Schema::create('user_addresses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');

                // Dane osobowe
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email');
                $table->string('phone');

                // Adres
                $table->string('address');
                $table->string('city');
                $table->string('postal_code');
                $table->string('country')->default('Polska');

                // Opcje
                $table->boolean('is_default')->default(false);

                $table->timestamps();

                // Indeksy
                $table->index('user_id');
                $table->index(['user_id', 'is_default']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }
};
