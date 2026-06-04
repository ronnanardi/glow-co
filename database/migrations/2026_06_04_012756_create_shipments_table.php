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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('courier'); // misal: JNE, TIKI, SiCepat
            $table->string('service')->nullable(); // misal: REG, YES
            $table->string('tracking_number')->nullable();
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->enum('status', ['pending', 'picked_up', 'in_transit', 'delivered', 'returned'])->default('pending');
            $table->timestamp('estimated_arrival')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
