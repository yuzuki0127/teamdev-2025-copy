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
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_responses_id');
            $table->unsignedBigInteger('survey_question_id');
            $table->unsignedBigInteger('survey_category_id');
            $table->integer('answer')->comment('回答 -2~2');
            $table->text('detail_answer')->nullable()->comment('詳細な回答');
            $table->text('solution_plan')->nullable()->comment('解決策プラン');
            $table->timestamps();

            // 外部キー制約: survey_responses_id は survey_responses テーブルの id を参照
            $table->foreign('survey_responses_id')
                  ->references('id')
                  ->on('survey_responses')
                  ->onDelete('cascade');

            // 外部キー制約: survey_question_id は survey_questions テーブルの id を参照
            $table->foreign('survey_question_id')
                  ->references('id')
                  ->on('survey_questions')
                  ->onDelete('cascade');

            // 外部キー制約: survey_category_id は survey_category テーブルの id を参照
            $table->foreign('survey_category_id')
                  ->references('id')
                  ->on('survey_categories')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_answers');
    }
};
