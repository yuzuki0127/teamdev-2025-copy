<x-app-layout>
    <x-analysis.nav />
    <div class="space-y-8 max-w-screen-lg mx-auto px-6 pt-15">
        @if (!$latestSurvey)
            <p class="text-center text-xl text-red-600 font-semibold pt-4">アンケート結果がありません</p>
        @else
        <div class="flex -mb-7.5">
            <button type="button" 
                onclick="window.history.back()"
                class="bg-white text-navy-default border-2 border-navy-default rounded-full shadow-xl py-1 px-6 text-base font-bold hover:bg-navy-default hover:text-white transition">
                ＜ 戻る
            </button>
        </div>        
            <section>
                <x-analysis.summary :latestSurvey="$latestSurvey" :data="$data" />
            </section>
            <section class="text-sm">
                <div class="flex items-center gap-4">
                    <div class="flex-grow h-px border-2 border-blue-default"></div>
                    <div><img src="{{ asset('images/su.png') }}" class="w-10 h-10"></div>
                    <div class="font-bold text-xl text-navy-default whitespace-nowrap">アンケート結果の詳細を表示します</div>
                    <div class="flex-grow h-px border-2 border-blue-default"></div>
                </div>

                {{-- 詳細表 --}}
                <div class="grid grid-cols-[55%_38%] gap-[7%] mx-12 my-10">
                    <div class="bg-white shadow-xl rounded-lg p-6">
                        <div class="mb-4 text-gray-800 font-semibold">
                            <h3 class="text-lg">各項目の平均と前回比</h3>
                            <div class="mx-4 mt-4">
                                <p>そう思わない（-2）～ そう思う（2）</p>
                                <div class="mt-3">
                                    <p>
                                        <span>数値が</span>
                                        <span class="text-orange-default">下がっている</span>
                                        <span>項目 =＞ 注意が必要な領域を示します。</span>
                                    </p>
                                    <p>
                                        <span>数値が</span>
                                        <span class="text-blue-default">上がっている</span>
                                        <span>項目 =＞ 改善が進んでいる領域</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <hr class="border-t-2 border-dashed border-navy-default">
                        <div class="grid grid-cols-[60%_20%_20%] mt-8 border-b border-gray-200">
                            <div>
                                <h4 class="font-semibold text-gray-800 px-2">項目</h4>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 px-1">平均値</h4>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 px-1">前回比</h4>
                            </div>
                        </div>
                        <div id="itemStats">
                            @foreach ($itemStats as $categoryName => $stats)
                                <div
                                    class="grid grid-cols-[60%_20%_20%] pt-3 pb-1 border-b border-dashed border-gray-400 text-gray-700">
                                    <div class="px-2">{{ $categoryName }}</div>
                                    <div class="px-1 font-semibold">
                                        {{ $stats['average'] !== null ? $stats['average'] : 'データ不足' }}
                                    </div>
                                    <div class="px-1 font-semibold">
                                        {{ $stats['diff'] !== null ? $stats['diff'] : 'N/A' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{-- 上位・下位 --}}
                    <div class="py-12">
                        <ul class="bg-white p-8 rounded-lg shadow-xl text-base font-semibold">
                            @if ($mostDecreased && $mostIncreased)
                                <li class="mb-4">
                                    <p>一番下がった項目は...</p>
                                    <p class="text-blue-default">{{ $mostDecreased['categoryName'] }} (差:
                                        {{ $mostDecreased['diff'] }})</p>
                                </li>
                                <hr class="border-t-2 border-dashed border-navy-default">
                                <li class="mt-4">
                                    <p>一番上がった項目は...</p>
                                    <p class="text-orange-default">{{ $mostIncreased['categoryName'] }} (差:
                                        {{ $mostIncreased['diff'] }})</p>
                                </li>
                            @else
                                <li class="mb-4">
                                    <p>前回データがありません</p>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div>
                    <!-- ✅ 折れ線グラフ -->
                    <div class="py-8">
                        <div class="sm:px-6 lg:px-8">
                            <div class="overflow-hidden p-6 bg-white rounded-lg shadow-xl">
                                <h3 class="font-semibold text-lg text-gray-800">平均値推移</h3>
                                <div class="relative w-full h-96">
                                    <canvas id="lineChart" class="w-full h-full"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- アイテムの動的コンテンツ -->
                    <div id="itemsContainer" class="mx-12 px-20 py-12 mt-12 bg-white rounded-lg shadow-xl">
                        @foreach ($itemStats as $categoryId => $stats)
                            <div class="py-8 px-30 mb-4" id="item-{{ $categoryId }}">
                                <div
                                    class="bg-blue-default text-white rounded-full shadow-xl mx-auto py-1.5 px-6 text-base font-bold w-96 flex items-center justify-between">
                                    <span class="w-2 h-2 block bg-white rounded-full"></span>
                                    <h3 class="text-base font-semibold">{{ $categoryId }}</h3>
                                    <span class="w-2 h-2 block bg-white rounded-full"></span>
                                </div>

                                <div class="mt-4">
                                    <!-- 平均と前回比の表示 -->
                                    <div class="flex items-center space-x-4">
                                        <p class="text-sm text-gray-500">平均</p>
                                        <p class="text-xl font-bold">{{ $stats['average'] }}</p>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <p class="text-sm text-gray-500">前回比</p>
                                        <p class="text-xl font-bold">{{ $stats['diff'] }}</p>
                                    </div>

                                    <!-- プログレスバーの表示 -->
                                    <div class="flex items-center pt-4 relative">
                                        <div class="w-full bg-gray-200 rounded-full h-6 relative">
                                            <div
                                                class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 bg-gray-400 h-12 w-0.5">
                                                <span
                                                    class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 text-base font-semibold text-gray-600">0</span>
                                            </div>
                                            <div class="bg-blue-default h-6 rounded-full"
                                                style="width: {{ ($stats['average'] + 2) * 25 }}%">
                                                <span
                                                    class="text-white text-sm font-semibold pl-2">{{ $stats['average'] }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 「詳細を見る」ボタン -->
                                    <div class="flex justify-end pt-4">
                                        <button type="button"
                                            class="toggle-details bg-white text-orange-default border-2 border-orange-default font-semibold py-2 px-4 rounded-lg hover:bg-orange-default hover:text-white transition"
                                            data-category-id="{{ $categoryId }}">
                                            詳細を見る
                                        </button>
                                    </div>

                                    <!-- 詳細コンテンツ（初期状態は非表示） -->
                                    <div id="detail-content-{{ $categoryId }}"
                                        class="mt-4 hidden p-4 bg-gray-100 rounded">
                                        @if (isset($itemStats[$categoryId]) && count($itemStats[$categoryId]['averages']) > 0)
                                            @foreach ($itemStats[$categoryId]['averages'] as $questionId => $qAverage)
                                                @php
                                                    $questionText = DB::table('survey_questions')
                                                        ->where('id', $questionId)
                                                        ->value('survey_question_text');
                                                    $qWidth = ($qAverage + 2) * 25;
                                                    $qWidth = $qWidth > 100 ? 100 : $qWidth;
                                                @endphp

                                                <div class="mb-12">
                                                    <h4 class="font-semibold mb-2">{{ $questionText }}</h4>
                                                    <div class="flex items-center space-x-4 mb-2">
                                                        <p class="text-sm text-gray-500">平均</p>
                                                        <p class="text-xl font-bold">{{ $qAverage }}</p>
                                                    </div>
                                                    <div class="flex items-center space-x-2 relative mb-4">
                                                        <div class="w-full bg-gray-200 rounded-full h-6 relative">
                                                            <div
                                                                class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 bg-gray-400 h-12 w-0.5">
                                                                <span
                                                                    class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 text-base font-semibold text-gray-600">0</span>
                                                            </div>
                                                            <div class="bg-green-600 h-6 rounded-full"
                                                                style="width: {{ $qWidth }}%">
                                                                <span
                                                                    class="text-white text-sm font-semibold pl-2">{{ $qAverage }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p>この項目には質問データがありません。</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <hr class="border-t-2 border-dashed border-navy-default">
                            <script>
                                window.lineLabels = @json($lineLabels);
                                window.lineData = @json($lineData);
                            </script>
                        @endforeach
                    </div>
                </div>
            </section>
        @endIf
    </div>
</x-app-layout>
