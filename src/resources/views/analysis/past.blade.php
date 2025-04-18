<x-app-layout>
    <x-analysis.nav />
    <div class="max-w-244 mx-auto pt-25">
        <div class="flex items-end pb-2 w-50">
            <a href="{{ url('/analysis') }}"
                class="h-8 font-bold border-2 border-blue-default bg-white text-blue-default px-4 py-1 shadow-xl rounded-full hover:bg-blue-default hover:text-white transition hover:border-2">
                < 分析と提案を見る
            </a>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex-grow h-px border-2 border-blue-default"></div>
            <div><img src="{{ asset('images/talking.png') }}" class="w-10 h-10"></div>
            <div class="font-bold text-xl text-navy-default whitespace-nowrap">過去の提案を見てみる</div>
            <div class="flex-grow h-px border-2 border-blue-default"></div>
        </div>
        @foreach ($plannings as $planning)
            <div>
                <div class="flex justify-center my-5">
                    <div
                        class="bg-blue-default text-white rounded-full shadow-xl py-2 px-6 text-base font-bold w-128 flex items-center justify-between">
                        <span class="w-3 h-3 block bg-white rounded-full"></span>
                        <span class="font-bold text-center block">{{ $planning->planning_name }}</span>
                        <span class="w-3 h-3 block bg-white rounded-full"></span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @foreach ($plannings as $planning)
                        @foreach ($planning->planningDetails as $detail)
                            <x-analysis.proposal :planning="$detail" />
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
