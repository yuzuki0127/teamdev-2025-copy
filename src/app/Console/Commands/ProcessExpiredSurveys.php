<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Survey;
use App\Models\Planning;
use Carbon\Carbon;
use App\Services\SurveyInferenceService;

class ProcessExpiredSurveys extends Command
{
    protected $signature = 'survey:process-expired';
    protected $description = '回答期限が過ぎた Survey を処理し、GPT推論を実行する';

    protected $inferenceService;

    public function __construct(SurveyInferenceService $inferenceService)
    {
        parent::__construct();
        $this->inferenceService = $inferenceService;
    }

    public function handle()
    {
        // 現在時刻を超えた Survey を取得
        $expiredSurveys = Survey::where('response_deadline', '<=', Carbon::now())->get();

        foreach ($expiredSurveys as $survey) {
            // 既にPlanningが作成されている場合はスキップする
            if (Planning::where('survey_id', $survey->id)->exists()) {
                $this->info("Survey ID: {$survey->id} は既にPlanningが存在するためスキップします。");
                continue;
            }
            
            $this->info("Processing survey ID: {$survey->id}");
            try {
                // サービスを利用してGPT推論を実行
                $this->inferenceService->runInference($survey);
                $this->info("Survey ID: {$survey->id} の推論処理が完了しました。");
            } catch (\Exception $e) {
                $this->error("Survey ID: {$survey->id} の処理中にエラーが発生しました: " . $e->getMessage());
            }
        }

        $this->info('全ての立案処理が完了しました。');
    }
}
