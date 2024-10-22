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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            // Define foreign key constraint with cascade delete
            $table->unsignedBigInteger('company_id')->nullable(); // Make the column nullable
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->string('category_name');
            $table->integer('order_index')->default(0);
            $table->string('status')->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
