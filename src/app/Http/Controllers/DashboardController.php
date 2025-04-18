<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Team;
use App\Models\Employee;
use App\Models\Industry;
use App\Models\CompanyCategory;
use App\Models\SurveyCategoryBasicTemplate;

class DashboardController extends Controller
{
    // 会社初回登録画面を表示するメソッド（最初のメソッドとして追加）
    public function companyFirstRegister()
    {
        $industries = Industry::all();
        $companyCategories = CompanyCategory::all();
        return view('dashboard.company-first-register', compact('industries', 'companyCategories'));
    }

    public function storeCompany(Request $request)
    {
        $user = User::current();
        // バリデーション
        $request->validate([
            'company_name'          => 'required|string|max:255',
            'industry'              => 'required|integer',
            'company_category'      => 'required|integer',
            'department'            => 'required|string|max:255',
            
            // employees 配列のバリデーション
            'employees'             => 'required|array',
            'employees.*.employee_name'      => 'required|string|max:255',
            'employees.*.employee_email'     => 'required|email|max:255',
            'employees.*.employee_birth_year'=> 'required|integer',
            'employees.*.employee_sex'      => 'required|in:0,1',
        ]);

        DB::beginTransaction();
        try {
            // Team テーブルに登録
            $team = Team::create([
                'company_name'       => $request->company_name,
                'team_name'          => $request->department,
                'company_category_id'=> $request->company_category,
                'industry_id'        => $request->industry,
            ]);

            // 認証ユーザーの team_id を更新
            $user->update(['team_id' => $team->id]);
            // 従業員情報を employees テーブルに登録
            foreach ($request->employees as $employee) {
                Employee::create([
                    'team_id'       => $team->id,
                    'employee_name' => $employee['employee_name'],
                    'email'         => $employee['employee_email'],
                    'year_of_birth' => $employee['employee_birth_year'],
                    'sex'           => $employee['employee_sex'],
                ]);
            }

            $teamId = $user->team_id;
            $team = Team::where('id', $teamId)->first();
            $categories = SurveyCategoryBasicTemplate::with('surveyQuestionBasicTemplates')->get();
            foreach ($categories as $categoryTemplate) {
                $category = $team->surveyCategoryTemplates()->create([
                    'team_id' => $teamId,
                    'survey_category_name' => $categoryTemplate->survey_category_name,
                    'editable' => 0,
                ]);
                foreach ($categoryTemplate->surveyQuestionBasicTemplates as $questionTemplate) {
                    $category->surveyQuestionTemplates()->create([
                        'team_id' => $teamId,
                        'survey_category_template_id' => $categoryTemplate->id,
                        'survey_question_text' => $questionTemplate->survey_question_text,
                        'editable' => 0,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('company.first-register')->with('error', '企業登録に失敗しました。');
        }
        return redirect()->route('dashboard.company-detail')->with('status', '企業登録に成功しました。');
    }

    public function detail()
    {
        $teamId = User::current()->team_id;
    
        $team = Team::findOrFail($teamId);
        $employees = Employee::where('team_id', $teamId)->get();
    
        $industries = Industry::all();
        $companyCategories = CompanyCategory::all();
        return view('dashboard.company-detail', compact('team', 'employees', 'industries', 'companyCategories'));
    }

    // 編集画面表示用メソッド
    public function edit()
    {
        $teamId = User::current()->team_id;
        $team = Team::with('employees')->findOrFail($teamId);
        $industries = Industry::all();
        $companyCategories = CompanyCategory::all();

        return view('dashboard.company-edit', compact('team', 'industries', 'companyCategories'));
    }

    // 更新処理用メソッド
    public function update(Request $request)
    {
        $request->validate([
            'company_name'          => 'required|string|max:255',
            'industry'              => 'required|integer',
            'company_category'      => 'required|integer',
            'department'            => 'required|string|max:255',
            'employees'             => 'required|array',
            'employees.*.employee_name'      => 'required|string|max:255',
            'employees.*.employee_email'     => 'required|email|max:255',
            'employees.*.employee_birth_year'=> 'required|integer',
            'employees.*.employee_sex'      => 'required|in:0,1',
        ]);

        DB::beginTransaction();
        try {
            $team = Team::findOrFail(Auth::user()->team_id);
            // 企業情報更新
            $team->update([
                'company_name'        => $request->company_name,
                'team_name'           => $request->department,
                'company_category_id' => $request->company_category,
                'industry_id'         => $request->industry,
            ]);
            // 既存の従業員情報を一旦削除して、再登録する方法（※要件に合わせて個別更新や削除処理に変更可能）
            Employee::where('team_id', $team->id)->delete();
            foreach ($request->employees as $employee) {
                Employee::create([
                    'team_id'       => $team->id,
                    'employee_name' => $employee['employee_name'],
                    'email'         => $employee['employee_email'],
                    'year_of_birth' => $employee['employee_birth_year'],
                    'sex'           => $employee['employee_sex'],
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', '企業情報の更新に失敗しました。');
        }

        return redirect()->route('dashboard.company-detail')->with('status', '企業情報を更新しました。');
    }
}
