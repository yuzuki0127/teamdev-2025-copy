<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Action;
use App\Models\ActionPeriod;
use App\Models\ActionPeriodDetail;
use App\Models\PlanningDetail;

class ActionSeeder extends Seeder
{
    public function run(): void
    {
        // Team 1 のアクションを手動で作成
        $this->createActionForTeam1();

        // Team 2 のアクションを手動で作成
        $this->createActionForTeam2();
    }

    /**
     * Team 1 のアクションを手動で作成
     */
    private function createActionForTeam1(): void
    {
        // Team 1 に該当する PlanningDetail を取得
        $planningDetail1 = PlanningDetail::where('planning_id', 1)
            ->first();

        if ($planningDetail1) {
            $action1 = Action::create([
                'team_id'     => 1,
                'title'       => $planningDetail1->title,
                'description' => $planningDetail1->description,
                'background'  => $planningDetail1->background,
                'purpose'     => $planningDetail1->purpose,
                'deadline'    => Carbon::now()->addDays(30),
                'stopped'     => 0,
                'is_completed'=> 0,
            ]);

            // ActionPeriod を 3つ作成
            for ($i = 1; $i <= 3; $i++) {
                $startDate = Carbon::create(2024, 4, 2)->addDays(($i - 1) * 9);
                $endDate = $startDate->copy()->addDays(9);

                $actionPeriod = ActionPeriod::create([
                    'action_id'         => $action1->id,
                    'period_title'      => "アクション {$i}",
                    'action_start_date' => $startDate,
                    'action_end_date'   => $endDate,
                    'is_completed'      => 0,
                ]);

                // ActionPeriodDetail を 3つ作成（タスク1~3）
                for ($j = 1; $j <= 3; $j++) {
                    $taskStartDate = $startDate->copy()->addDays(($j - 1) * 3);
                    $taskEndDate = $taskStartDate->copy()->addDays(3);

                    ActionPeriodDetail::create([
                        'action_period_id'         => $actionPeriod->id,
                        'action_detail_title'      => "タスク {$j}",
                        'action_detail_start_date' => $taskStartDate,
                        'action_detail_end_date'   => $taskEndDate,
                        'action_detail_manager'    => "Manager {$j}",
                    ]);
                }
            }
        }
    }

    /**
     * Team 2 のアクションを手動で作成
     */
    private function createActionForTeam2(): void
    {
        $planningDetail2 = PlanningDetail::where('planning_id', 2)
            ->first();

        if ($planningDetail2) {
            $action2 = Action::create([
                'team_id'     => 2,
                'title'       => $planningDetail2->title,
                'description' => $planningDetail2->description,
                'background'  => $planningDetail2->background,
                'purpose'     => $planningDetail2->purpose,
                'deadline'    => Carbon::now()->addDays(90),
                'stopped'     => 0,
                'is_completed'=> 0,
            ]);

            // ActionPeriod を 3つ作成
            for ($i = 1; $i <= 3; $i++) {
                $startDate = Carbon::create(2024, 4, 2)->addDays(($i - 1) * 9);
                $endDate = $startDate->copy()->addDays(9);

                $actionPeriod = ActionPeriod::create([
                    'action_id'         => $action2->id,
                    'period_title'      => "アクション {$i}",
                    'action_start_date' => $startDate,
                    'action_end_date'   => $endDate,
                    'is_completed'      => 0,
                ]);

                // ActionPeriodDetail を 3つ作成（タスク1~3）
                for ($j = 1; $j <= 3; $j++) {
                    $taskStartDate = $startDate->copy()->addDays(($j - 1) * 3);
                    $taskEndDate = $taskStartDate->copy()->addDays(3);

                    ActionPeriodDetail::create([
                        'action_period_id'         => $actionPeriod->id,
                        'action_detail_title'      => "タスク {$j}",
                        'action_detail_start_date' => $taskStartDate,
                        'action_detail_end_date'   => $taskEndDate,
                        'action_detail_manager'    => "Manager {$j}",
                    ]);
                }
            }
        }
    }
}
