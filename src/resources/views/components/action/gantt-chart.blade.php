<div class="container mx-auto px-4 py-2 text-3 w-270">
    {{-- <h1 class="text-2xl font-bold mb-6">ガントチャート</h1> --}}
    <div class="overflow-x-auto border rounded-lg">
        <table class="min-w-max table-fixed border-collapse">
            <thead class="bg-gray-200">
                <tr>
                    <th class="w-10 p-2 border-r border-gray-200 text-center"></th>
                    <!-- タスク情報の列 (必要に応じて幅や列数を調整) -->
                    <th class="w-48 p-2 border-r border-gray-200 text-left">タスク名</th>
                    <th class="w-28 p-2 border-r border-gray-200 text-left">担当者</th>
                    <th class="w-24 p-2 border-r border-gray-200 text-left">開始日</th>
                    <th class="w-24 p-2 border-r border-gray-200 text-left">終了日</th>

                    <!-- 日付ヘッダ (ガント部分) -->
                    @foreach ($dateRange as $date)
                        <th class="w-8 p-2 border-r border-gray-200 text-center">
                            {{ $date->format('m/d') }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach ($action->actionPeriods as $period)
                    <!-- 親タスク(ActionPeriod) の行 -->
                    <tr class="bg-gray-100">
                        <!-- チェックボックス -->
                        <td class="p-2 border-r border-gray-200 text-center">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-cyan-600 period-toggle"
                                data-period-id="{{ $period->id }}" {{ $period->is_completed ? 'checked' : '' }}>
                        </td>
                        <!-- タスク名(親) -->
                        <td class="p-2 border-r border-gray-200 font-semibold">
                            {{ $period->period_title }}
                        </td>
                        <td class="p-2 border-r border-gray-200"></td>
                        <!-- 開始日 -->
                        <td class="p-2 border-r border-gray-200 font-semibold">
                            {{ \Carbon\Carbon::parse($period->action_start_date)->format('Y-m-d') }}
                        </td>
                        <!-- 終了日 -->
                        <td class="p-2 border-r border-gray-200 font-semibold">
                            {{ \Carbon\Carbon::parse($period->action_end_date)->format('Y-m-d') }}
                        </td>
                        <!-- ガントバー(親タスク) -->
                        @foreach ($dateRange as $date)
                            @php
                                $inRange = $date->between($period->action_start_date, $period->action_end_date);
                            @endphp
                            <td class="relative border-r border-gray-200 h-6">
                                @if ($inRange)
                                    <div class="absolute inset-0 bg-blue-default rounded border border-gray-100"></div>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    <!-- 子タスク(ActionPeriodDetail) の行 -->
                    @foreach ($period->actionPeriodDetails as $detail)
                        <tr>
                            <td class="p-2 border-r border-gray-200 text-center"></td>
                            <!-- 子タスク名 -->
                            <td class="py-2 pl-4 pr-2 border-r border-gray-200">
                                {{ $detail->action_detail_title }}
                            </td>
                            <!-- 担当者 -->
                            <td class="p-2 border-r border-gray-200">
                                {{ $detail->action_detail_manager ?? '未設定' }}
                            </td>
                            <!-- 開始日(子タスク) -->
                            <td class="p-2 border-r border-gray-200">
                                {{ \Carbon\Carbon::parse($detail->action_detail_start_date)->format('Y-m-d') }}
                            </td>
                            <!-- 終了日(子タスク) -->
                            <td class="p-2 border-r border-gray-200">
                                {{ \Carbon\Carbon::parse($detail->action_detail_end_date)->format('Y-m-d') }}
                            </td>
                            <!-- ガントバー(子タスク) -->
                            @foreach ($dateRange as $date)
                                @php
                                    $detailInRange = $date->between(
                                        $detail->action_detail_start_date,
                                        $detail->action_detail_end_date,
                                    );
                                @endphp
                                <td class="relative border-r border-gray-200 h-6">
                                    @if ($detailInRange)
                                        <div
                                            class="absolute inset-0 bg-blue-default bg-opacity-50 rounded border border-white">
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
