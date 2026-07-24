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
        Schema::create('processed_webhooks', function (Blueprint $table) {

            $table->id();

            $table->uuid('event_id')->unique();

            $table->string('provider');

            $table->string('reference');

            $table->string('status');

            $table->string('payload_hash');

            $table->timestamp('processed_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processed_webhooks');
    }
};
