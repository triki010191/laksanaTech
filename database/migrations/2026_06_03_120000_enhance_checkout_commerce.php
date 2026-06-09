<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domain_extensions', function (Blueprint $table) {
            $table->decimal('compare_price', 15, 2)->nullable()->after('price');
        });

        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('label')->nullable();
            $table->enum('type', ['fixed', 'percent'])->default('fixed');
            $table->decimal('value', 15, 2);
            $table->decimal('min_order_total', 15, 2)->nullable();
            $table->unsignedInteger('max_uses')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_codes');

        Schema::table('domain_extensions', function (Blueprint $table) {
            $table->dropColumn('compare_price');
        });
    }
};
