<x-app-layout>
    <x-action.nav />
    <div class="max-w-7xl mx-auto">
        @if ($action)
            {{-- 実行中のプロダクト --}}
            <div class="px-8 pt-18 flex justify-end">
                <form id="stop-action-form" method="POST" action="{{ route('action.stop', $action->id) }}">
                    @csrf
                    <button id="stop-action-btn" type="submit"
                        class="bg-orange-default text-white font-bold shadow-xl flex hover:bg-red-600 text-base px-8 py-1.5 rounded-full border-2 transition">
                        <span class="flex items-center"><img src="{{ asset('images/trash.png') }}" class="w-4 h-4"></span><span class="pl-2">施策を中止する</span>
                    </button>
                </form>
            </div>
            <div class="gap-4 mx-10">
                <x-action.overview :action="$action" />
            </div>
            {{-- 進捗度表示 --}}
            <x-action.progress-display :action="$action" :actionProgress="$actionProgress" />
            <div class="flex justify-end px-20 py-4">
                <form id="complete-action-form" method="POST" action="{{ route('action.complete', $action->id) }}">
                    @csrf
                    <button id="complete-action-btn" @if ($actionProgress !== 100.0) disabled @endif
                        class="text-lg font-bold text-white px-8 py-1 rounded-full shadow-xl border-2 transition
                          {{ $actionProgress === 100.0 ? 'bg-blue-default hover:bg-white hover:text-blue-default border-blue-default' : 'bg-gray-600' }}">
                        施策を完了にする
                    </button>
                </form>
            </div>
            <!-- ガントチャート -->
            <x-action.gantt-chart :action="$action" :dateRange="$dateRange" />
        @else
            <div class="text-center text-gray-500 text-2xl text-semibold mt-10">
                現在、実行中のアクションはありません。
            </div>
        @endif
    </div>
</x-app-layout>
