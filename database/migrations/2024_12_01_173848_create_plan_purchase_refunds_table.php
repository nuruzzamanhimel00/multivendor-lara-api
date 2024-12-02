<?php

use App\Enums\RefundStatus;
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
        Schema::create('plan_purchase_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('plan_purchases')->onDelete('cascade');
            $table->date('refund_no');
            $table->date('refund_date');
            $table->decimal('refund_amount', 10, 2);
            $table->text('reason')->nullable();
            $table->string('status')->default(RefundStatus::PENDING->value)->comment(enumCasesToSting(RefundStatus::cases()));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_purchase_refunds');
    }
};
