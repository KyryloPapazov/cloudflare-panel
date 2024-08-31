<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cloudflare_account_id');
            $table->string('name');
            $table->string('status');
            $table->string('cloudflare_zone_id')->nullable();
            $table->enum('ssl_mode', ['off', 'flexible', 'full', 'strict'])->default('off');
            $table->timestamps();


            $table->foreign('cloudflare_account_id')->references('id')->on('cloudflare_accounts')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
