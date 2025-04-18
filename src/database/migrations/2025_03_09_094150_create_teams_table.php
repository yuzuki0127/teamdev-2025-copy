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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('team_name');
            $table->unsignedBigInteger('company_category_id');
            $table->unsignedBigInteger('industry_id');
            $table->timestamps();

            // 外部キー制約
            $table->foreign('company_category_id')->references('id')->on('company_categories');
            $table->foreign('industry_id')->references('id')->on('industries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
