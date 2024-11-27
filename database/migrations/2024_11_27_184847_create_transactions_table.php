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
            $table->id(); 
            $table->string('title'); 
            $table->text('description')->nullable(); 
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['expense', 'income']); 
            $table->date('transaction_date'); 
            $table->foreignId('category_id')->constrained('categories'); 
            $table->foreignId('account_id')->constrained('accounts'); 
            $table->foreignId('user_id')->constrained('users'); 
            $table->boolean('is_recurring')->default(false); 
            $table->string('attachment')->nullable(); 
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
