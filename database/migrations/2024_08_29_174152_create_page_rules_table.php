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
        Schema::create('pageRule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->constrained()->onDelete('cascade');  // Ссылка на домен
            $table->string('target_url');  // Целевой URL для правила
            $table->json('actions');  // JSON поле для хранения действий правила
            $table->string('status')->default('active');  // Статус правила
            $table->string('cloudflare_rule_id')->nullable();  // ID правила в Cloudflare
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pageRule');
    }
};
