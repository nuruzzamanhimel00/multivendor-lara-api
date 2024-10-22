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
        Schema::create('user_plans', function (Blueprint $table) {
            $table->id();
            // Define foreign key constraint with cascade delete
            $table->unsignedBigInteger('user_id')->nullable(); // Make the column nullable
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('plan_id')->nullable(); // Make the column nullable
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->unsignedBigInteger('company_id')->nullable(); // Make the column nullable
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('document')->nullable();
            $table->string('status')->default('pending')->comment('pending,accepted,declined');
            $table->date('status_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_plans');
    }
};
