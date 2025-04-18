<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 mb-4 mx-8">
    <div class="flex flex-col items-center p-4 bg-white shadow rounded">
        <img src="{{ asset('images/company.svg') }}" alt="company" class="w-15 h-15 mb-2">
        <div class="text-lg font-bold text-navy-default">01 企業情報の取得</div>
        <div class="text-lg font-bold text-navy-default"> あなたの会社に合った提案を</div>
        <p class="text-sm mt-2 text-gray-700 leading-relaxed">
            新規登録時に入力していただいた企業情報を取得します。
        </p>
    </div>

    <div class="flex flex-col items-center p-4 bg-white shadow rounded">
        <img src="{{ asset('images/survey.svg') }}" alt="survey" class="w-15 h-15 mb-2">
        <div class="text-lg font-bold text-navy-default">02 アンケート情報の取得</div>
        <div class="text-lg font-bold text-navy-default">課題解決に</div>
        <p class="text-sm mt-2 text-gray-700 leading-relaxed">
            過去・直近のアンケート結果を取得し、現場の企業の状況を確認します。
        </p>
    </div>

    <div class="flex flex-col items-center p-4 bg-white shadow rounded">
        <img src="{{ asset('images/inference.svg') }}" alt="inference" class="w-15 h-15 mb-2">
        <div class="text-lg font-bold text-navy-default">03 AIによる施策提案</div>
        <div class="text-lg font-bold text-navy-default">03 AIによる施策提案</div>
        <p class="text-sm mt-2 text-gray-700 leading-relaxed">
            AIによって、現場の現状を基に施策を提案します。
            <br>
            以下AIのプロンプト
            <br>
            「{{ $detailPrompts }}」
        </p>
    </div>
</div>
