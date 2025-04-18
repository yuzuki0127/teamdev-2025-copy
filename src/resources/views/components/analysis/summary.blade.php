<div class="text-center text-blue-default text-3xl font-bold pt-6">
    <h1>{{ $latestSurvey->survey_name }}</h1>
</div>
<div class="flex items-center gap-4">
    <div class="flex-grow h-px border-2 border-blue-default"></div>
    <div><img src="{{ asset('images/talking.png') }}" class="w-10 h-10"></div>
    <div class="font-bold text-xl text-navy-default whitespace-nowrap">分析結果を表示</div>
    <div class="flex-grow h-px border-2 border-blue-default"></div>
</div>
<div class="mx-auto bg-white overflow-hidden shadow-xl sm:rounded-lg w-250 p-10">
    <x-analysis.AIsummary :latestSurvey="$latestSurvey" />
    <hr class="border-t-2 border-dashed border-navy-default mt-8">
    <div class="flex justify-between pt-6">
        <div class="px-4">
            <div class="relative w-full h-76">
                <canvas id="radarChart" class="w-full h-full"></canvas>
            </div>
        </div>
        <div class="py-8 flex flex-col justify-between gap-8">
            <div class="text-sm px-4">
                <span>内側に凹んでいる =></span>
                <span class="border-b-4 border-orange-default font-bold">改善が必要</span>
            </div>
            <ul class="grid grid-cols-2 gap-2 font-bold text-sm">
                @foreach ($latestSurvey->surveyCategories as $index => $category)
                    <li>{{ $index + 1 }}. {{ $category->survey_category_name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<script>
    window.chartData = {
        labels: @json(range(1, count($latestSurvey->surveyCategories))),
        data: @json($data),
    };
</script>
