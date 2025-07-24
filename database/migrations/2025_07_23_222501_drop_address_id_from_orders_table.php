<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Usuń foreign key constraint
            $table->dropForeign(['address_id']);
            // Usuń kolumnę
            $table->dropColumn('address_id');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('address_id')->nullable()->after('user_id');
            $table->foreign('address_id')->references('id')->on('user_addresses')->onDelete('set null');
        });
    }
};
