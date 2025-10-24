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
        Schema::table('image_categories', function (Blueprint $table) {
            // Thêm cột slug sau cột title
            $table->string('slug')->nullable()->after('title');
            
            // Thêm unique index cho slug
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('image_categories', function (Blueprint $table) {
            // Xóa unique index trước
            $table->dropUnique(['slug']);
            
            // Xóa cột slug
            $table->dropColumn('slug');
        });
    }
};
