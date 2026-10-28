<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sights', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('photo_profile'); 
            $table->json('photos')->nullable();
            $table->string('mini_description', 75); 
            $table->text('description'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sights');
    }
};
