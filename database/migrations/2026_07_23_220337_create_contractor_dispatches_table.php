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
        Schema::create('contractor_dispatches', function (Blueprint $table) {

            $table->id();

            $table->foreignId('work_order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('external_reference')
                ->nullable();

            $table->string('status');

            $table->unsignedInteger('attempt')
                ->default(1);

            $table->json('request_payload');

            $table->json('response_payload')
                ->nullable();

            $table->text('error')
                ->nullable();

            $table->timestamp('dispatched_at')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractor_dispatches');
    }
};
