<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->foreignId('template_id')->nullable()->after('service_category_id')->constrained()->nullOnDelete();
            $table->string('preview_image')->nullable()->after('description');
            $table->string('demo_url')->nullable()->after('preview_image');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropConstrainedForeignId('template_id');
            $table->dropColumn(['preview_image', 'demo_url']);
        });
    }
};
