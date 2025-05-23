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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained();
            $table->foreignId('apartment_type_id')->nullable()->constrained();
            $table->unsignedInteger('size')->nullable();
            $table->string('name');
            $table->unsignedInteger('capacity_adults');
            $table->unsignedInteger('capacity_children');
            $table->unsignedInteger('bathrooms')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
