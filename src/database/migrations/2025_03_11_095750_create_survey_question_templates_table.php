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
        Schema::create('survey_question_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('survey_category_template_id');
            $table->text('survey_question_text');
            $table->boolean('editable')->default(1)->comment('質問編集可否 0が不可 1が可');
            $table->timestamps();

            // 外部キー制約
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('survey_category_template_id')->references('id')->on('survey_category_templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_question_templates');
    }
};
