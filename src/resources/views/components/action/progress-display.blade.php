<div id="progress-display-container" class="bg-white rounded-md shadow mb-6 w-231 m-auto pb-2 mt-5">
    <x-action.progress-percent :actionProgress="$actionProgress" />
    <div class="mb-5 mx-20 mt-10">
        <div class="relative">
            <div class="w-full bg-gray-200 rounded-full h-2"></div>
            <div class="absolute inset-0 bg-orange-500 rounded-full h-2" style="width: {{ $actionProgress }}%"></div>
            <div class="absolute inset-0 flex justify-between items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-orange-500"></div>
                @foreach ($action->actionPeriods as $period)
                    <div
                        class="w-8 h-8 rounded-full flex items-center justify-center
                        {{ $period->is_completed ? 'bg-orange-500' : 'bg-gray-200' }}">
                    </div>
                @endforeach
            </div>
        </div>
        <div class="flex justify-between mt-4">
            <div class="text-center text-sm flex font-bold"><img src="{{ asset('images/flag.png') }}" class="w-4 h-4">開始
            </div>
            @foreach ($action->actionPeriods as $period)
                <div class="text-center font-bold text-sm">{{ $period->period_title }}</div>
            @endforeach
        </div>
    </div>
</div>
