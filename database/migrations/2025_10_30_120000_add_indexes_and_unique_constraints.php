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
		Schema::table('categories', function (Blueprint $table) {
			// Unique slug để đảm bảo không trùng
			$table->unique('slug');
			// Index cho các cột hay lọc
			$table->index('parent_id');
			$table->index('is_active');
		});

		Schema::table('posts', function (Blueprint $table) {
			$table->unique('slug');
			$table->index('category_id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('categories', function (Blueprint $table) {
			$table->dropUnique(['slug']);
			$table->dropIndex(['parent_id']);
			$table->dropIndex(['is_active']);
		});

		Schema::table('posts', function (Blueprint $table) {
			$table->dropUnique(['slug']);
			$table->dropIndex(['category_id']);
		});
	}
};


