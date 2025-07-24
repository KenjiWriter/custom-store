<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Usuń stare kolumny adresowe (jeśli istnieją)
            $columns = Schema::getColumnListing('orders');

            if (in_array('first_name', $columns)) {
                $table->dropColumn([
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'address',
                    'city',
                    'postal_code',
                    'country'
                ]);
            }

            // Dodaj nowe kolumny
            if (!in_array('address_id', $columns)) {
                $table->unsignedBigInteger('address_id')->nullable()->after('user_id');
            }

            if (!in_array('notes', $columns)) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['address_id', 'notes']);

            // Przywróć stare kolumny
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('country')->default('Polska');
        });
    }
};
