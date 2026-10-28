<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->string('favoritable_type')->nullable(); 
            $table->unsignedBigInteger('favoritable_id')->nullable(); 
        });
    }

    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            //
        });
    }
};
