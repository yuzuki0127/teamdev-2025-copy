<div class="mx-auto mb-12 p-6 bg-white shadow-md rounded-lg relative pl-12.5 pr-12.5 pt-7.5 pb-5  w-112.5">
    <div class="grid grid-cols-[75%_25%] mb-4 gap-2">
        <h2 class="text-xl font-bold mb-4 relative pl-6 border-double border-b-2 border-orange-default">
            {{ $planning['title'] }}
        </h2>
        <div class="flex flex-col gap-2 text-xs font-bold">
            <span class="bg-orange-default text-white px-4 py-1 rounded">
                コスト{{ $planning['cost'] }}
            </span>
            <span class="bg-orange-default text-white px-4 py-1 rounded">
                優先度{{ $planning['priority'] }}
            </span>
        </div>
    </div>
    {{-- 提案の説明文 --}}
    <p class="text-sm   leading-relaxed">
        {{ $planning['description'] }}
    </p>
    {{-- 提案の背景 --}}
    <h3 class="font-bold mt-6 text-base">
        提案の背景
    </h3>
    <p class="mt-3 text-sm leading-relaxed">
        {{ $planning['background'] }}
    </p>

    {{-- 目的 --}}
    <h3 class="font-bold mt-6 text-base">
        目的
    </h3>
    <p class="mt-3 text-sm leading-relaxed">
        {{ $planning['purpose'] }}
    </p>

    {{-- 仕切り線（破線） --}}
    <hr class="border-t-2 border-dashed border-navy-default mt-8">

    {{-- スケジュールの想定 --}}
    <h3 class="font-bold mt-8 text-base cursor-pointer hover:opacity-70" onclick="toggleSchedule({{ $planning->id }})">
        <span class="border-b">スケジュールの想定 <span id="scheduleArrow{{ $planning->id }}">▼</span></span>
    </h3>
    <div id="scheduleSection{{ $planning->id }}" class="transition-max-height overflow-hidden" style="max-height: 0;">
        @foreach ($planning->planningPeriods as $period)
            <div class="mt-6 text-sm font-bold">
                <span>{{ $period->planning_period }}：</span>
                <span>{{ $period->planning_title }}</span>
            </div>

            {{-- スケジュール表 --}}
            <table class="mt-3 w-full border-collapse text-sm">
                <colgroup>
                    <col style="width: 30%;">
                    <col style="width: 70%;">
                </colgroup>
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-2 py-1">期間</th>
                        <th class="border px-2 py-1">内容</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($period->planningPeriodDetails as $schedule)
                        <tr class="">
                            <td class="border px-2 py-1">
                                {{ $schedule['planning_detail_period'] }}
                            </td>
                            <td class="border px-2 py-1">
                                {{ $schedule['planning_detail_title'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>

    {{-- 推論の過程ボタン、仕切り線、フォーム --}}
    <div class="mt-8">
        <hr class="border-t border-gray-500 mt-4 w-full">
        <div class="flex justify-center items-end gap-4">
            @if (request()->routeIs('action.create'))
                <div class="flex justify-center mt-4">
                    <button type="button" onclick="fillCreateForm(this)" data-title="{{ $planning['title'] }}"
                        data-description="{{ $planning['description'] }}"
                        data-background="{{ $planning['background'] }}" data-purpose="{{ $planning['purpose'] }}"
                        class="bg-white text-navy-default border-2 border-navy-default rounded-full shadow-xl py-2 px-6 text-base font-bold hover:bg-navy-default hover:text-white transition">
                        ✓ 案を代入する
                    </button>
                </div>
            @elseif (request()->routeIs('analysis.index') ||
                    request()->routeIs('planning.select') ||
                    request()->routeIs('planning.past'))
                <form method="POST" action="{{ route('planning.select') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="planning_detail_id" value="{{ $planning->id }}">
                    <div class="flex justify-center">
                        <button type="submit"
                            class="bg-white text-navy-default border-2 border-navy-default rounded-full shadow-xl py-2 px-6 text-base font-bold hover:bg-navy-default hover:text-white transition">
                            ✓ 案を選択する
                        </button>
                    </div>
                </form>
            @endif
            <button
                onclick='showProcessOfReasoning(
                    @json($planning['process_of_reasoning']), 
                    @json($planning['cost_detail']),
                    @json($planning['priority_detail'])
                )'
                class="text-white bg-orange-default text-xs font-bold px-2 py-0 rounded-full shadow shadow-gray-500 h-8 hover:bg-white hover:text-orange-default hover:border-2 hover:border-orange-default transition">
                推論の背景を見る
            </button>
        </div>
    </div>
</div>
