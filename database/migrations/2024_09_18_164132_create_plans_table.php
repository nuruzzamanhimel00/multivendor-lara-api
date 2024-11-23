<?php

use App\Enums\PlanPeriodEnum;
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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('limit_items')->default(0)->comment('0 = unlimited');
            $table->integer('limit_orders')->default(0)->comment('0 = unlimited');
            $table->decimal('price', 10, 2);
            $table->string('period')->nullable()->comment(enumCasesToSting(PlanPeriodEnum::cases()));
            $table->text('description')->nullable();
            $table->text('features')->nullable();

            $table->integer('enable_orders')->default(2)->comment('1=enable,2=disable');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
