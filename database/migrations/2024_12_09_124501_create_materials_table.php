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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->integer('order')->default(0);
            $table->string('annotation')->nullable();
            $table->text('text')->nullable();
            $table->foreignIdFor(App\Models\Team::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(App\Models\MaterialCategory::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(App\Models\User::class)->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
