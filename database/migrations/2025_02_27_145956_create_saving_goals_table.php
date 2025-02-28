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
        Schema::create('saving_goals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('target_amount', 10, 2);
            $table->date('target_date');
            $table->decimal('saved_amount', 10, 2)->default(0);
            $table->foreignId('profile_id')->constrained('profiles')->onDelete('cascade');
            $table->foreignId('category_id') ->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saving_goals');
    }
};
