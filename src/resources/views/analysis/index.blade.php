<x-app-layout>
    <x-analysis.nav />
    <div class="space-y-8 max-w-screen-lg mx-auto px-6 pt-15">
        @if ($latestSurvey)
            <x-analysis.summary :latestSurvey="$latestSurvey" :data="$data" />
        @else
            <p class="text-center text-xl text-red-600 font-semibold pt-4">分析結果はありません</p>
        @endIf
        <div class="flex items-center gap-4 mt-8">
            <div class="flex-grow h-px border-2 border-blue-default"></div>
            <div><img src="{{ asset('images/su.png') }}" class="w-10 h-10"></div>
            <div class="font-bold text-xl text-navy-default whitespace-nowrap">この分析結果から、AIが以下の改善策を提案します</div>
            <div class="flex-grow h-px border-2 border-blue-default"></div>
        </div>
        <div class="relative flex justify-center">
            {{-- 施策立案 --}}
            <div class="max-w-244 mx-auto">
                <div class="mx-auto">
                    <div class="relative w-32 h-10 flex justify-center items-end mx-auto">
                        <div class="absolute -bottom-0.5  w-8 h-0.5 bg-blue-700 origin-right rotate-45"></div>
                        <div class="absolute -bottom-0.5 right-1/2 w-8 h-0.5 bg-blue-700 origin-left -rotate-45"></div>
                        <div class="absolute bottom-4 right-1/2 -translate-x-1/2">
                            <div class="w-2 h-2 bg-orange-default rounded-full border border-blue-default"></div>
                        </div>
                    </div>
                    <div class="bg-white border-t-2 border-b-2 border-blue-default pb-5 px-5 mb-4">
                        <span
                            class="text-orange-default text-lg font-bold border-b block text-center m-2">推論の方法を見る🔍</span>
                        {{-- プロンプト --}}
                        <div class="flex justify-center items-center ">
                            <!-- 左側の吹き出し -->
                            <div
                                class="border-2 border-blue-500 bg-white p-4 rounded-lg shadow-lg max-w-sm relative group">
                                <!-- 吹き出しの三角形部分 (before擬似要素) -->
                                <div
                                    class="absolute top-1/2 left-full transform -translate-y-1/2 border-[15px] border-transparent border-l-[15px] border-l-blue-500">
                                </div>
                                <!-- 吹き出し内のコンテンツ -->
                                <div class="flex items-center space-x-2 w-70 gap-12">
                                    <div>
                                        <div class="font-bold text-center">直近・過去の<br>アンケート</div>
                                        <img src="{{ asset('images/survey.svg') }}" class="" alt="">
                                    </div>
                                    <div>
                                        <div class="font-bold text-center">企業の情報</div>
                                        <img src="{{ asset('images/company.svg') }}" class="" alt="">
                                    </div>
                                </div>
                            </div>
                            <!-- 中央の円とアイコン -->
                            <div class="flex items-center justify-center p-4">
                                <div class="text-white">
                                    <img src="{{ asset('images/prompt.svg') }}" alt="">
                                </div>
                            </div>
                            <div
                                class="border-2 border-orange-default bg-white p-5 rounded-lg shadow-lg max-w-sm relative">
                                <!-- 吹き出しの三角形部分 (左向き) -->
                                <div
                                    class="absolute top-1/2 left-[-30px] mt-3.75 transform -translate-y-1/2 border-[15px] border-transparent border-r-[15px] border-r-orange-default">
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div>
                                        <div class="font-bold text-lg text-center m-4 border-b-2 border-dashed border-orange-default">プロンプト</div>
                                        <div class="font-bold text-sm">
                                            特に、事前に実施したアンケート結果において、各質問の点数が低く、低評価が顕著な項目、または全体の平均評価が低い項目に注目し、そこから抽出される問題点を解決するための施策を中心に提案してください。
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <x-flow-steps :detail_prompts="$detail_prompts" /> --}}
                    </div>
                </div>
                <div class="flex items-end pb-2 pt-6 w-50 ml-auto">
                    <a href="{{ url('/analysis/past-plan') }}"
                        class="h-8 font-bold border-2 border-orange-default bg-white text-orange-default px-4 py-1 shadow-xl rounded-full hover:bg-orange-default hover:text-white transition hover:border-2">
                        過去の提案を見る >
                    </a>
                </div>
                @if ($plannings)
                <div class="grid grid-cols-2 gap-4">
                        @foreach ($plannings->planningDetails as $planning)
                            <x-analysis.proposal :planning="$planning" />
                        @endforeach
                    </div>
                    @else
                        <p class="text-center text-red-600 text-xl font-bold mb-10">AIによる施策立案がありません</p>
                    @endif
            </div>
        </div>
    </div>
</x-app-layout>
