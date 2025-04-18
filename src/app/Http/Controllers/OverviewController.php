<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\User;
use App\Models\Action;
use Carbon\Carbon;

class OverviewController extends Controller
{
    public function index()
    {
        $user = User::current();
        $surveys = Survey::where('team_id', $user->team_id)->get();
        if ($surveys->isNotEmpty()) {
            $latestSurvey = $surveys->sortByDesc('created_at')->first();
            $surveyReception = $latestSurvey->response_deadline > Carbon::now() ? '回答受付中' : '受付終了';
            
            $action = Action::with('actionPeriods.actionPeriodDetails')
            ->where('team_id', $user->team_id)
            ->where('stopped', false)
            ->where('is_completed', false)
            ->latest()
            ->first();
        } else {
            $latestSurvey = null;
            $surveyReception = null;
            $action = null;
        }
            
        if ($surveys->isEmpty()) {
            $todo = "survey";
        } else if ($action) {
            $todo = "action";
        } else if ($latestSurvey->response_deadline < Carbon::now() && !$action) {
            $todo = "analysis";
        } else if ($latestSurvey->response_deadline > Carbon::now()) {
            $todo = "waiting";
        };

        $actionProgress = $this->calculateActionProgress($action);

        // ビューにデータを渡す
        return view('dashboard', compact(
            'latestSurvey',
            'surveyReception',
            'todo',
            'action',
            'actionProgress'
        ));
    }

    /**
     * アクションの進捗度（%）を計算する
     *
     * @param  \App\Models\Action|null $action
     * @return int
     */
    private function calculateActionProgress($action)
    {
        if (!$action || !$action->actionPeriods) {
            return 0;
        }

        $completedPeriods = $action->actionPeriods->where('is_completed', 1)->count();
        $totalPeriods     = $action->actionPeriods->count();

        return $totalPeriods > 0
            ? round(($completedPeriods / $totalPeriods) * 100)
            : 0;
    }
}
