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
        Schema::create('ssl_commerce_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('store_id');
            $table->string('store_passwd');
            $table->string('init_url');
            $table->string('success_url');
            $table->string('fail_url');
            $table->string('cancel_url');
            $table->string('ipn_url');
            $table->string('currency');
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ssl_commerce_accounts');
    }
};
