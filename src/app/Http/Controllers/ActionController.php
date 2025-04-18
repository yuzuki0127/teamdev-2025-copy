<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Action;
use App\Models\ActionPeriod;
use App\Models\PlanningDetail;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;

class ActionController extends Controller
{
    public function showAdopting()
    {
        $team = User::current()->team_id;
        $selectedIds = session('selected_planning_details', []);
        $selectedPlannings = PlanningDetail::with(['planningPeriods.planningPeriodDetails'])
            ->whereIn('id', $selectedIds)
            ->get();
        $employees = Employee::where('team_id', $team)->get();

        return view('action.create', compact('selectedPlannings', 'employees'));
    }

    public function storeAdopting(Request $request)
    {
        $request->validate([
            'planning_title' => 'required|string|max:255',
            'description'    => 'nullable|string',
            'background'     => 'nullable|string',
            'purpose'        => 'nullable|string',
            'planning_detail' => 'required|array',
            'planning_detail.*.title' => 'required|string|max:255',
        ]);
        foreach ($request->input('planning_detail') as $index => $planningDetail) {
            if (isset($planningDetail['planning_detail_period'])) {
                foreach ($planningDetail['planning_detail_period'] as $taskIndex => $task) {
                    $request->validate([
                        "planning_detail.$index.planning_detail_period.$taskIndex.task_title" => 'required|string|max:255',
                        "planning_detail.$index.planning_detail_period.$taskIndex.task_employee_id" => 'required|exists:employees,id',
                        "planning_detail.$index.planning_detail_period.$taskIndex.task_start_date" => 'required|date',
                        "planning_detail.$index.planning_detail_period.$taskIndex.task_end_date" => 'required|date|after_or_equal:planning_detail.$index.planning_detail_period.$taskIndex.task_start_date',
                    ]);
                }
            }
        }

        $maxEndDate = null;
        foreach ($request->input('planning_detail') as $planningDetail) {
            if (isset($planningDetail['end_date']) && $planningDetail['end_date']) {
                $candidate = Carbon::parse($planningDetail['end_date']);
                if (is_null($maxEndDate) || $candidate->gt($maxEndDate)) {
                    $maxEndDate = $candidate;
                }
            }
            if (isset($planningDetail['planning_detail_period']) && is_array($planningDetail['planning_detail_period'])) {
                foreach ($planningDetail['planning_detail_period'] as $task) {
                    if (isset($task['task_end_date']) && $task['task_end_date']) {
                        $taskCandidate = Carbon::parse($task['task_end_date']);
                        if (is_null($maxEndDate) || $taskCandidate->gt($maxEndDate)) {
                            $maxEndDate = $taskCandidate;
                        }
                    }
                }
            }
        }

        $action = Action::create([
            'team_id'      => auth()->user()->team_id,
            'title'        => $request->input('planning_title'),
            'description'  => $request->input('description'),
            'background'   => $request->input('background'),
            'purpose'      => $request->input('purpose'),
            'deadline'     => $maxEndDate,
            'stopped'      => 0,
            'is_completed' => 0,
        ]);

        foreach ($request->input('planning_detail') as $planningDetail) {
            $minTaskStart = null;
            $maxTaskEnd = null;
            if (isset($planningDetail['planning_detail_period']) && is_array($planningDetail['planning_detail_period'])) {
                foreach ($planningDetail['planning_detail_period'] as $task) {
                    $taskStart = Carbon::parse($task['task_start_date']);
                    $taskEnd = Carbon::parse($task['task_end_date']);
                    if (is_null($minTaskStart) || $taskStart->lt($minTaskStart)) {
                        $minTaskStart = $taskStart;
                    }
                    if (is_null($maxTaskEnd) || $taskEnd->gt($maxTaskEnd)) {
                        $maxTaskEnd = $taskEnd;
                    }
                }
            }
            $periodStart = $minTaskStart ? $minTaskStart->toDateString() : (isset($planningDetail['start_date']) ? $planningDetail['start_date'] : null);
            $periodEnd = $maxTaskEnd ? $maxTaskEnd->toDateString() : (isset($planningDetail['end_date']) ? $planningDetail['end_date'] : null);

            $actionPeriod = $action->actionPeriods()->create([
                'period_title'      => $planningDetail['title'],
                'action_start_date' => $periodStart,
                'action_end_date'   => $periodEnd,
                'is_completed'      => 0,
            ]);

            if (isset($planningDetail['planning_detail_period']) && is_array($planningDetail['planning_detail_period'])) {
                foreach ($planningDetail['planning_detail_period'] as $task) {
                    $employee = Employee::find($task['task_employee_id']);
                    $actionPeriod->actionPeriodDetails()->create([
                        'action_detail_title'      => $task['task_title'],
                        'action_detail_start_date' => $task['task_start_date'],
                        'action_detail_end_date'   => $task['task_end_date'],
                        'action_detail_manager'    => $employee ? $employee->employee_name : null,
                    ]);
                }
            }
        }

        return redirect()->route('action.tasks')->with('status', 'アクションを保存しました！');
    }

    public function tasks()
    {
        $user = User::current();
        $action = Action::with('actionPeriods.actionPeriodDetails')
            ->where('team_id', $user->team_id)
            ->where('stopped', false)
            ->where('is_completed', false)
            ->latest()
            ->first();

        if (!$action) {
            return redirect()->route('action.create');
        }

        $allPeriods = collect();
        foreach ($action->actionPeriods as $period) {
            $allPeriods->push(Carbon::parse($period->action_start_date));
            $allPeriods->push(Carbon::parse($period->action_end_date));
            foreach ($period->actionPeriodDetails as $detail) {
                $allPeriods->push(Carbon::parse($detail->action_detail_start_date));
                $allPeriods->push(Carbon::parse($detail->action_detail_end_date));
            }
        }
        $minDate = $allPeriods->min();
        $maxDate = $allPeriods->max();

        $dateRange = [];
        $current = $minDate->copy();
        while ($current->lte($maxDate)) {
            $dateRange[] = $current->copy();
            $current->addDay();
        }

        $totalPeriods = $action->actionPeriods->count();
        $completedPeriods = $action->actionPeriods->where('is_completed', 1)->count();
        $actionProgress = $totalPeriods > 0
            ? round(($completedPeriods / $totalPeriods) * 100)
            : 0;

        return view('action.tasks', compact('action', 'dateRange', 'actionProgress'));
    }

    public function togglePeriod(Request $request)
    {
        $request->validate([
            'period_id' => 'required|integer|exists:action_periods,id',
        ]);
        
        $period = ActionPeriod::findOrFail($request->period_id);
        $period->is_completed = !$period->is_completed;
        $period->save();
    
        return response()->json([
            'success' => true,
            'is_completed' => $period->is_completed
        ]);
    }

    public function stopAction(Action $action)
    {
        $action->stopped = 1;
        $action->save();

        return redirect()->route('action.tasks')->with('status', 'タスクが中止されました');
    }

    public function completeAction(Action $action)
    {
        $action->is_completed = 1;
        $action->save();

        return redirect()->route('dashboard')->with('status', '施策が完了しました！');
    }
}
