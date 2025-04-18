<div class="@if(Route::is('dashboard')) @else grid grid-cols-[70%,30%] @endif gap-6 font-semibold">
    @if ($latestSurvey->survey_summary)
        <p>
            {{ $latestSurvey->survey_summary }}
        </p>
    @else
        <p class="text-red-600">アンケートのAIによる要約がありません</p>
    @endif
    <div class="flex items-end pb-2">
        @if (Route::is('analysis.index'))
            <a href="{{ route('analysis.detail') }}"
                class="h-8 border-1.5 bg-orange-default text-white px-4 py-1 shadow-xl rounded-full hover:bg-white hover:text-orange-default transition hover:border-2 hover:border-orange-default">
                アンケートの詳細を見る >
            </a>
        @elseif (Route::is('analysis.detail'))
            <a href="{{ route('analysis.index') }}"
                class="h-8 border-1.5 bg-blue-default text-white px-4 py-1 shadow-xl rounded-full hover:bg-white hover:text-blue-default transition hover:border-2 hover:border-blue-default">
                アンケートの概要に戻る >
            </a>
        @else
        @endif
    </div>
</div>
