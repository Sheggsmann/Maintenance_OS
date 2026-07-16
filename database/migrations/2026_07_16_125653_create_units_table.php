<?php

use App\Enums\UnitStatusEnum;
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
        Schema::create('units', function (Blueprint $table) {

            $table->id();

            $table->foreignId('property_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('tenant_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('unit_number');

            $table->unsignedInteger('floor')->nullable();

            $table->string('type');

            $table->string('status')
                ->default(UnitStatusEnum::VACANT->value);

            $table->unsignedTinyInteger('bedrooms')->nullable();

            $table->unsignedTinyInteger('bathrooms')->nullable();

            $table->decimal('area', 8, 2)->nullable();

            $table->decimal('monthly_rent', 12, 2)->nullable();

            $table->text('description')->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->unique([
                'property_id',
                'unit_number',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
