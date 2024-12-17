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
        Schema::create('team_joins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');    // id инициатора
            $table->unsignedBigInteger('team_id');    // id группы
            $table->string('note')->nullable();       // заметка (обоснование)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_joins');
    }
};
