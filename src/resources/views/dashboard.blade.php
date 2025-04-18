<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative">
                <div class="px-12 text-gray-900">
                    <section class="mb-8 bg-white shadow-md rounded-md border-3 border-blue-default">
                        <div class="px-8 py-4 font-bold">
                            <h1 class="text-xl text-blue-default mb-2">ToDo</h1>
                            @if ($todo === 'survey')
                                <div class="flex gap-20.25">
                                    <p class="text-lg">
                                        <span>次は </span>
                                        <span class="text-blue-default">アンケートの送信</span>
                                        <span>をしましょう！</span>
                                    </p>
                                    <a href="{{ route('survey.create') }}" class="flex justify-center">
                                        <button
                                            class="border-2 border-blue-default rounded-full px-4 py-1 shadow text-blue-default font-semibold hover:bg-blue-default hover:text-white transition">
                                            <p>アンケートへ</p>
                                        </button>
                                    </a>
                                </div>
                            @elseif($todo === 'action')
                                <div class="flex gap-20.25">
                                    <p class="text-lg">
                                        <span>次は </span>
                                        <span class="text-blue-default">実行中の施策を確認</span>
                                        <span>しましょう！</span>
                                    </p>
                                    <a href="{{ route('action.tasks') }}" class="flex justify-center">
                                        <button
                                            class="border-2 border-blue-default rounded-full px-4 py-1 shadow text-blue-default font-semibold hover:bg-blue-default hover:text-white transition">
                                            <p>実行中の施策へ</p>
                                        </button>
                                    </a>
                                </div>
                            @elseif($todo === 'analysis')
                                <div class="flex gap-20.25">
                                    <p class="text-lg">
                                        <span>次は </span>
                                        <span class="text-blue-default">分析結果の確認・施策を作成</span>
                                        <span>しましょう！</span>
                                    </p>
                                    <a href="{{ route('analysis.index') }}" class="flex justify-center">
                                        <button
                                            class="border-2 border-blue-default rounded-full px-4 py-1 shadow text-blue-default font-semibold hover:bg-blue-default hover:text-white transition">
                                            <p>分析結果の確認へ</p>
                                        </button>
                                    </a>
                                    </di>
                                @elseif($todo === 'waiting')
                                    <p class="text-lg">
                                        <span>現在アンケートを受付中です。しばらくお待ち下さい</span>
                                    </p>
                            @endif
                        </div>
                    </section>
                    <section class="mb-8 grid grid-cols-2 gap-4">
                        <div class="bg-white px-8 py-4 shadow-md rounded-md">
                            @if ($latestSurvey)
                                <h2 class="text-blue-default text-lg font-semibold mb-2">最新のアンケート</h2>
                                <div class="flex justify-between pl-4 pr-8 font-bold">
                                    <p class="text-lg font-bold border-b-4 border-orange-default">
                                        {{ $latestSurvey->survey_name }}</p>
                                    <p class="text-base">{{ $surveyReception }}</p>
                                </div>
                                <div class="font-semibold mt-7">
                                    <p class="flex gap-6">
                                        <span>配布日:</span>
                                        <span>{{ Carbon\Carbon::parse($latestSurvey->created_at)->format('Y年m月d日') }}</span>
                                    </p>
                                    <p class="flex gap-6 pt-2">
                                        <span>集計日:</span>
                                        <span>{{ Carbon\Carbon::parse($latestSurvey->response_deadline)->format('Y年m月d日') }}</span>
                                    </p>
                                </div>
                                <hr class="border-t-2 border-dashed border-navy-default my-4">
                                <a href="{{ url('survey/create') }}" class="flex justify-center">
                                    <button
                                        class="border-2 border-blue-default rounded-full px-4 py-1 shadow text-blue-default font-semibold hover:bg-blue-default hover:text-white transition">
                                        <p>アンケートへ ></p>
                                    </button>
                                </a>
                            @else
                                <p class="text-lg text-red-600 font-semibold">
                                    現在アンケートはありません。
                                </p>
                            @endif
                        </div>
                        <div class="p-7 bg-white shadow-md rounded-md">
                            <h2 class="text-blue-default text-lg font-semibold mb-2">分析結果/提案</h2>
                            @if ($latestSurvey)
                                <x-analysis.AIsummary :latestSurvey="$latestSurvey" />
                                <hr class="border-t-2 border-dashed border-navy-default my-4">
                                <a href="{{ url('analysis') }}" class="flex justify-center">
                                    <button
                                        class="border-2 border-blue-default rounded-full px-4 py-1 shadow text-blue-default font-semibold hover:bg-blue-default hover:text-white transition">
                                        <p>分析結果と提案へ ></p>
                                    </button>
                                </a>
                            @else
                                <p class="text-lg text-red-600 font-semibold">
                                    現在分析結果はありません。
                                </p>
                            @endif
                        </div>
                    </section>

                    <!-- タスク管理 -->
                    <section class="task-management bg-white p-6 shadow-md rounded-md mb-8 overflow-hidden">
                        <h2 class="text-blue-default text-lg font-semibold -mb-2">タスク管理</h2>
                        @if (!$action)
                            <p class="text-lg text-red-600 font-semibold">
                                現在実行中の施策はありません。
                            </p>
                        @else
                            <div class="">
                                <x-action.progress-percent :actionProgress="$actionProgress" />
                            </div>
                            <div class="">
                                <x-action.overview :action="$action" />
                            </div>
                        @endif
                    </section>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
