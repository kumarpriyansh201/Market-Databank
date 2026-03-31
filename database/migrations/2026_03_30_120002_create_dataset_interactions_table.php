<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dataset_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('market_data_id')->constrained('market_data')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('interaction_type', ['view', 'download']);
            $table->timestamps();

            $table->index(['market_data_id', 'interaction_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dataset_interactions');
    }
};
