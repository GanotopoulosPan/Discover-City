<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->foreignId('custom_list_id')->nullable()->constrained('custom_lists')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            //
        });
    }
};
