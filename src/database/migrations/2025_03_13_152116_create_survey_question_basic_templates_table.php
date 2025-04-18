<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_question_basic_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_category_basic_template_id');
            $table->text('survey_question_text');
            $table->timestamps();

            // 外部キー制約: survey_category_basic_templates テーブルの id を参照
            $table->foreign('survey_category_basic_template_id', 'fk_sqbt_scbt')
                ->references('id')
                ->on('survey_category_basic_templates')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_question_basic_templates');
    }
};
