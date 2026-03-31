<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_datasets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('market_data_id')->constrained('market_data')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'market_data_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_datasets');
    }
};
