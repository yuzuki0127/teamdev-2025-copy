<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class PlanningSeeder extends Seeder
{
    public function run()
    {
        // 他のシーダーを呼び出す
        $this->call([
            // 他に必要なシーダーがあればここに追加
        ]);

        // スケジュールの実行
        Artisan::call('schedule:run');
    }
}
