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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->string('tran_id')->unique();
            $table->string('val_id')->nullable();
            $table->string('cus_details');
            $table->string('delivery_status');
            $table->string('ship_details');
            $table->float('amount', 10);
            $table->string('currency')->default('BDT');
            $table->string('payment_status')->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
