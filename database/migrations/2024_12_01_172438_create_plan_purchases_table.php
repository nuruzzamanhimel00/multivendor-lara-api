<?php

use App\Enums\GlobalStatus;
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
        Schema::create('plan_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');

            $table->date('start_date');
            $table->date('end_date');

            $table->integer('limit_items')->default(0)->comment('0 = unlimited');
            $table->integer('limit_orders')->default(0)->comment('0 = unlimited');

            $table->integer('enable_orders')->default(2)->comment('1=enable,2=disable');
            $table->decimal('total_price', 10, 2);
            $table->boolean('is_refunded')->default(false);
            $table->string('status')->default(GlobalStatus::ACTIVE)->comment(enumCasesToSting(GlobalStatus::cases()));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_purchases');
    }
};
