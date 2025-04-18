<div class="w-full">
    <div
        class="bg-blue-default text-white rounded-full py-2 px-6 text-base font-bold w-128 flex items-center justify-between mb-4 shadow-lg ml-29">
        <span class="w-3 h-3 block bg-white rounded-full"></span>
        <span class="font-bold text-center block border-b-2 border-dashed border-white"> {{ $action->title }}</span>
        <span class="w-3 h-3 block bg-white rounded-full"></span>
    </div>
    <div class="m-auto p-6 bg-white shadow-md rounded-md border-2 border-navy-default w-231">
        <div class="relative">
            <div class="mb-5">
                <div>{{ $action->description }}</div>
            </div>
            <hr class="border-t-2 border-dashed border-navy-default mb-3">
            <div class="flex justify-between gap-15">
                <div class="mb-5">
                    <h4 class="font-bold mb-1">提案の背景</h4>
                    <div>{{ $action->background }}</div>
                </div>
                <div class="mb-2">
                    <h4 class="font-bold mb-1">目的</h4>
                    <div>{{ $action->purpose }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
