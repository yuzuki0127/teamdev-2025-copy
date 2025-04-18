<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg relative w-220 mx-auto">
                <!-- 編集ボタン（右上に配置） -->
                <a href="{{ route('dashboard.company-edit') }}" class="absolute top-4 right-4 bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">
                    編集
                </a>
                <div class="pt-10 px-12.5">
                    <h2 class="text-2xl font-bold text-primary mb-6 text-center">企業情報詳細</h2>
                    
                    <!-- 企業情報 -->
                    <div class="mb-8">
                        <div class="flex justify-center mb-7">
                            <div class="bg-blue-default text-white font-bold py-2 w-full text-left">
                                <span class="pl-2">企業情報</span>
                            </div>
                        </div>
                        <div class="space-y-2 mx-auto w-100">
                            <div class="flex w-full">
                                <p class=" font-semibold w-24">会社名</p>
                                <p class=" ml-20 text-left flex-1">{{ $team->company_name }}</p>
                            </div>
                            <div class="flex w-full">
                                <p class=" font-semibold w-24">業種</p>
                                <p class=" ml-20 text-left flex-1">{{ $team->industry->industry_name ?? '未設定' }}</p>
                            </div>
                            <div class="flex w-full">
                                <p class=" font-semibold w-24">企業規模</p>
                                <p class=" ml-20 text-left flex-1">{{ $team->companyCategory->company_category_name ?? '未設定' }}</p>
                            </div>
                            <div class="flex w-full">
                                <p class=" font-semibold w-24">部署名</p>
                                <p class=" ml-20 text-left flex-1">{{ $team->team_name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- 従業員情報 -->
                    <div>
                        <div class="flex justify-center">
                            <div class="bg-blue-default text-white font-bold py-2 w-full text-left">
                                <spa class="pl-2">従業員情報</spa>
                            </div>
                        </div>
                        <div class="space-y-1">
                            @foreach($employees as $employee)
                            <div class="p-4 rounded-lg">
                                <div class="flex space-x-8 justify-center w-150 mx-auto border-b-2 border-black pb-4">
                                    <div class="">
                                        <p class="mt-1">{{ $employee->employee_name }}</p>
                                    </div>
                                    <div class="">
                                        <p class="mt-1">{{ $employee->email }}</p>
                                    </div>
                                    <div class="">
                                        <p class="mt-1">{{ $employee->year_of_birth }}</p>
                                    </div>
                                    <div class="">
                                        <p class="mt-1">
                                            {{ $employee->sex == \App\Models\Employee::SEX_MALE ? '女性' : '男性' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
