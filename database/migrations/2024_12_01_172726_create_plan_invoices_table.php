<?php

use App\Enums\InvoiceStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plan_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('plan_purchases')->onDelete('cascade');
            $table->date('invoice_no');
            $table->date('invoice_date');
            $table->decimal('price', 10, 2);
            $table->string('status')->default(InvoiceStatus::PAID)->comment(enumCasesToSting(InvoiceStatus::cases()));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_invoices');
    }
};
