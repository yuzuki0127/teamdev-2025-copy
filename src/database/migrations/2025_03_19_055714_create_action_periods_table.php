<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('action_periods', function (Blueprint $table) {
            $table->id(); // PK
            $table->foreignId('action_id');
            $table->string('period_title', 255);
            $table->timestamp('action_start_date'); // 必須
            $table->timestamp('action_end_date');   // 必須
            $table->boolean('is_completed')->default(0)->comment('完了状況 0が未完了 1が完了');
            $table->timestamps(); // created_at, updated_at

            // 外部キー制約
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('action_periods');
    }
};

