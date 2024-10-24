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
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('id');
            $table->decimal('value', 10, 2);
            $table->text('description');
            $table->enum('method', ['debit_card', 'credit_card']);
            $table->integer('cardNumber');
            $table->string('cardHolderName');
            $table->date('cardExpirationDate');
            $table->integer('cardCvv');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
