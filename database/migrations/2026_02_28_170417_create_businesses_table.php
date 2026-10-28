<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('name'); 
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->string('website')->nullable(); 
            
            $table->string('category'); 
            $table->string('type')->nullable(); 
            
            $table->string('ama')->nullable(); 

            $table->string('age_group')->nullable(); 
            $table->string('duration')->nullable(); 
            $table->string('difficulty')->nullable(); 
            $table->string('schedule')->nullable(); 

            $table->text('description'); 
            $table->text('minidescription'); 
            $table->json('amenities')->nullable(); 

            $table->json('photos')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};