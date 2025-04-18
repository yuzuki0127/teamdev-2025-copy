<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Industry;
use App\Models\CompanyCategory;
use App\Models\Team;
use App\Models\Employee;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. 業種(Industry)の作成
        $industries = [
            ['industry_name' => 'IT・通信'],
            ['industry_name' => '金融・保険'],
            ['industry_name' => '製造'],
            ['industry_name' => 'サービス'],
            ['industry_name' => 'メーカー'],
            ['industry_name' => '商社'],
            ['industry_name' => 'マスコミ'],
            ['industry_name' => '官公庁・公社・団体'],
        ];

        foreach ($industries as $industryData) {
            Industry::create($industryData);
        }

        // 2. 会社区分(CompanyCategory)の作成
        $companyCategories = [
          ['company_category_name' => '大手企業'],
          ['company_category_name' => '中堅企業'],
          ['company_category_name' => 'グローバル企業'],
          ['company_category_name' => 'スタートアップ企業'],
          ['company_category_name' => 'フリーランス・個人事業主'],
        ];

        foreach ($companyCategories as $categoryData) {
            CompanyCategory::create($categoryData);
        }

        // 3. チーム(Team)の作成
        // ※ここでは、各チームの company_category_id や industry_id は上記で登録した順番に合わせています
        $teams = [
            [
                'company_name'      => 'テックテスト',
                'team_name'         => '開発テストチーム',
                'company_category_id' => 4,
                'industry_id'       => 1,
            ],
            [
                'company_name'      => 'ファイナンスグループ',
                'team_name'         => '投資チーム',
                'company_category_id' => 2,
                'industry_id'       => 2,
            ]
        ];

        foreach ($teams as $teamData) {
            Team::create($teamData);
        }

        // 4. 従業員(Employee)の作成
        $employees = [
            [
                'team_id'       => 1,
                'employee_name' => '田中 太郎',
                'email'         => 'taro@example.com',
                'sex'           => 1,
                'year_of_birth' => 1985,
            ],
            [
                'team_id'       => 1,
                'employee_name' => '佐藤 花子',
                'email'         => 'hanako@example.com',
                'sex'           => 0,
                'year_of_birth' => 1990,
            ],
            [
                'team_id'       => 1,
                'employee_name' => '鈴木 一郎',
                'email'         => 'ichiro2@example.com',
                'sex'           => 1,
                'year_of_birth' => 1988,
            ],
            [
                'team_id'       => 1,
                'employee_name' => '山田 花子',
                'email'         => 'hanako2@example.com',
                'sex'           => 0,
                'year_of_birth' => 1995,
            ],
            [
                'team_id'       => 1,
                'employee_name' => '高橋 健',
                'email'         => 'ken@example.com',
                'sex'           => 1,
                'year_of_birth' => 1983,
            ],
            [
                'team_id'       => 1,
                'employee_name' => '斉藤 彩',
                'email'         => 'aya@example.com',
                'sex'           => 0,
                'year_of_birth' => 1991,
            ],
            [
                'team_id'       => 2,
                'employee_name' => '藤田 陽子',
                'email'         => 'yoko@example.com',
                'sex'           => 0,
                'year_of_birth' => 1992,
            ],
            [
                'team_id'       => 2,
                'employee_name' => '田中 花子',
                'email'         => 'hanako3@example.com',
                'sex'           => 0,
                'year_of_birth' => 1993,
            ],
            [
                'team_id'       => 2,
                'employee_name' => '佐藤 一郎',
                'email'         => 'ichiro3@example.com',
                'sex'           => 1,
                'year_of_birth' => 1994,
            ],
            [
                'team_id'       => 2,
                'employee_name' => '鈴木 美咲',
                'email'         => 'misaki2@example.com',
                'sex'           => 0,
                'year_of_birth' => 1991,
            ],
            [
                'team_id'       => 2,
                'employee_name' => '高橋 優子',
                'email'         => 'yuko@example.com',
                'sex'           => 0,
                'year_of_birth' => 1990,
            ]
        ];

        foreach ($employees as $employeeData) {
            Employee::create($employeeData);
        }
    }
}
