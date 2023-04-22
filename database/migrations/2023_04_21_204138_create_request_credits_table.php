<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('request_credits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('creationDate')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('amount_requested')->nullable(false);
            $table->integer('amount_to_repay')->nullable(false);
            $table->string('product')->nullable(false);
            $table->enum('status', ['accordé', 'rejeté'])->nullable(false);
            $table->dateTime('dueDate')->nullable();
            $table->string('phoneNumber')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_credits');
    }
};
