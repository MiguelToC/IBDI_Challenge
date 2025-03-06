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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Código de venta único
            $table->string('customer_name');
            $table->enum('customer_id_type', ['DNI', 'RUC']);
            $table->string('customer_id_number');
            $table->string('customer_email')->nullable();
            $table->foreignId('user_id')->constrained('users'); // Relación con usuario (vendedor)
            $table->decimal('total_amount', 10, 2);
            $table->timestamp('sale_date')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
