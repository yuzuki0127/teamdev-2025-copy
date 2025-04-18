<x-app-layout>
    <x-action.nav />
    <div class="max-w-screen-lg mx-auto space-y-8 px-4">
        {{-- セクションヘッダー（選択した案の表示） --}}
        <div class="flex items-center gap-4 pt-15">
            <div class="flex-grow h-px border-2 border-blue-default"></div>
            <div><img src="{{ asset('images/select.png') }}" class="w-10 h-10"></div>
            <div class="font-bold text-xl text-navy-default whitespace-nowrap">選択した案の表示</div>
            <div class="flex-grow h-px border-2 border-blue-default"></div>
        </div>
        {{-- Splide スライダー --}}
        @if ($selectedPlannings->count() >= 2)
            <div class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach ($selectedPlannings as $planning)
                            <li class="splide__slide">
                                <x-analysis.proposal :planning="$planning"></x-analysis>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="splide__arrows">
                    <button class="splide__arrow splide__arrow--prev !bg-blue-default !text-white">&lt;</button>
                    <button class="splide__arrow splide__arrow--next !bg-blue-default !text-white">&gt;</button>
                </div>
            </div>
        @elseif($selectedPlannings->count() === 1)
            <ul class="w-120 mx-auto">
                @foreach ($selectedPlannings as $planning)
                    <li class="splide__slide">
                        <x-analysis.proposal :planning="$planning"></x-analysis>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-center">
                <p class="text-red-500 text-xl font-bold">選択した案はありません</p>
            </div>
        @endIf

        <!-- 以下、案を採用するフォームなどのコンテンツ -->
        <div id="actionForm" class="flex items-center gap-4">
            <div class="flex-grow h-px border-2 border-blue-default"></div>
            <div><img src="{{ asset('images/search.png') }}" class="w-10 h-10"></div>
            <div class="font-bold text-xl text-navy-default whitespace-nowrap">案を採用する</div>
            <div class="flex-grow h-px border-2 border-blue-default"></div>
        </div>

        {{-- フォーム全体 --}}
        <div class="bg-white px-30 py-6 rounded shadow overflow-x-auto">
            <form method="POST" id="sendActionCreateForm" action="{{ route('action.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="font-semibold block mb-1">案のタイトル</label>
                        <input type="textarea" name="planning_title"
                            class="w-full h-10 px-3 border border-gray-300 rounded"row="2" required />
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">詳細</label>
                        <input type="textarea" name="description" class="w-full h-10 px-3 border border-gray-300 rounded"row="2"
                            required />
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">提案の背景</label>
                        <input type="textarea" name="background" class="w-full h-10 px-3 border border-gray-300 rounded"row="2"
                            required />
                    </div>
                    <div>
                        <label class="font-semibold block mb-1">目的</label>
                        <input type="textarea" name="purpose" class="w-full h-10 px-3 border border-gray-300 rounded"row="2"
                            required />
                    </div>
                </div>

                <hr class="my-6 border-t-2 border-dashed border-blue-default">

                <div>
                    <label class="font-semibold block mb-2">スケジュールの想定</label>
                    <div class="overflow-x-auto">
                        <table class="table-fixed w-full border text-sm">
                            <thead class="bg-blue-default text-white">
                                <tr>
                                    <th class="p-2 border w-[45%]">タスク名</th>
                                    <th class="p-2 border w-[20%]">担当者</th>
                                    <th class="p-2 border w-[15%]">開始日</th>
                                    <th class="p-2 border w-[15%]">終了日</th>
                                    <th class="p-2 border w-[5%]"></th>
                                </tr>
                            </thead>
                            <tbody id="schedule-table-body">
                                <!-- 初期の主要工程行（index 0 とする） -->
                                <tr class="bg-gray-100">
                                    <td class="border p-2">
                                        <input type="textarea" placeholder="主要工程名" name="planning_detail[0][title]"
                                            class="w-full h-8 border rounded" rows="2" required />
                                    </td>
                                    <td class="border p-2"></td>
                                    <td class="border p-2"></td>
                                    <td class="border p-1"></td>
                                    <td class="border p-1 bg-gray-100"></td>
                                </tr>
                                <!-- 初期の具体タスク行（主要工程 0 に紐づくもの） -->
                                <tr>
                                    <td class="border pl-8 pr-2 py-2">
                                        <input type="textarea" placeholder="具体タスク名"
                                            name="planning_detail[0][planning_detail_period][0][task_title]"
                                            class="w-full h-8 border rounded" rows="2" required />
                                    </td>
                                    <td class="border p-2">
                                        <select name="planning_detail[0][planning_detail_period][0][task_employee_id]"
                                            class="appearance-none w-full h-8 pl-2 pr-8 py-0 border rounded bg-white text-sm">
                                            <option value="">従業員を選択</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->employee_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="border p-2">
                                        <input name="planning_detail[0][planning_detail_period][0][task_start_date]"
                                            class="text-sm w-full h-8 flatpickr border rounded px-1" type="text"
                                            placeholder="開始日" required />
                                    </td>
                                    <td class="border p-2">
                                        <input name="planning_detail[0][planning_detail_period][0][task_end_date]"
                                            class="text-sm w-full h-8 flatpickr border rounded px-1" type="text"
                                            placeholder="終了日" required />
                                    </td>
                                    <td class="border p-1"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex justify-center gap-4">
                        <button type="button"
                            class="text-sm font-semibold bg-blue-white block text-blue-default border-2 border-blue-default rounded-md px-4 py-1 shadow hover:bg-blue-default hover:text-white transition"
                            onclick="addMajorRow()">主要工程を追加</button>
                        <button type="button"
                            class="text-sm font-semibold bg-blue-white block text-blue-default border-2 border-blue-default rounded-md px-4 py-1 shadow hover:bg-blue-default hover:text-white transition"
                            onclick="addTaskRow()">具体タスクを追加</button>
                    </div>
                </div>

                <hr class="my-6 border-t border-gray-200">
                <div class="flex justify-center mt-4">
                    <button type="submit"
                        class="px-6 py-2 border-2 border-blue-default text-blue-default font-bold rounded-full hover:bg-blue-default hover:text-white transition shadow">
                        確定して採用する
                    </button>
                </div>
            </form>
        </div>
    </div>

    <template id="employeeOptions">
        <option value="">従業員を選択</option>
        @foreach ($employees as $employee)
            <option value="{{ $employee->id }}">{{ $employee->employee_name }}</option>
        @endforeach
    </template>
</x-app-layout>
