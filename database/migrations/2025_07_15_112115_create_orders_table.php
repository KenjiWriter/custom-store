<?php
// filepath: database/migrations/2025_07_24_000003_recreate_orders_table_clean.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Usuń starą tabelę jeśli istnieje
        Schema::dropIfExists('orders');

        // Utwórz nową, czystą tabelę orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();

            // TYLKO PODSTAWOWE RELACJE
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->nullable()->constrained('user_addresses')->onDelete('set null');

            // STATUSY I PŁATNOŚCI
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->enum('payment_method', ['card', 'blik', 'paypal', 'transfer', 'cash_on_delivery'])->nullable();

            // KWOTY
            $table->decimal('total_amount', 10, 2);

            // DANE PŁATNOŚCI
            $table->text('payment_data')->nullable(); // JSON z danymi płatności
            $table->timestamp('payment_date')->nullable();

            $table->timestamps();

            // INDEKSY
            $table->index('user_id');
            $table->index('address_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('order_number');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
