<x-app-layout>
    <div>
        <div class="max-w-4xl mx-auto p-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-primary mb-6 text-center">企業情報編集</h2>
                    
                    <form action="{{ route('dashboard.company-update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <!-- 企業情報 -->
                        <div class="pb-6">
                            <div class="flex justify-center mb-12 ">
                                <div class="bg-blue-default text-white font-bold py-2 w-full text-left">
                                    <span class="pl-2">企業情報</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-6">
                                <!-- 会社名 -->
                                <div>
                                    <x-input-label for="company_name" value="会社名" required />
                                    <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" placeholder="例：株式会社POSSE" value="{{ old('company_name', $team->company_name) }}" />
                                    <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                                </div>

                                <!-- 業種 -->
                                <div>
                                    <x-input-label for="industry" value="業種" required />
                                    <select id="industry" name="industry" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">選択してください</option>
                                        @foreach ($industries as $industry)
                                            <option value="{{ $industry->id }}" {{ old('industry', $team->industry_id) == $industry->id ? 'selected' : '' }}>
                                                {{ $industry->industry_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('industry')" class="mt-2" />
                                </div>

                                <!-- 会社区分 -->
                                <div>
                                    <x-input-label for="company_category" value="会社区分" required />
                                    <select id="company_category" name="company_category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">選択してください</option>
                                        @foreach ($companyCategories as $category)
                                            <option value="{{ $category->id }}" {{ old('company_category', $team->company_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->company_category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('company_category')" class="mt-2" />
                                </div>

                                <!-- 部署名 -->
                                <div>
                                    <x-input-label for="department" value="部署名" required />
                                    <x-text-input id="department" name="department" type="text" class="mt-1 block w-full" placeholder="例：人事部" value="{{ old('department', $team->team_name) }}" />
                                    <x-input-error :messages="$errors->get('department')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- 従業員情報 -->
                        <div id="employee-list">
                            <div class="flex justify-center mb-8 mt-16">
                                <div class="bg-blue-default text-white font-bold py-2 w-full text-left">
                                    <span class="pl-2">従業員情報</span>
                                </div>
                            </div>
                            
                            @if($team->employees->count() > 0)
                                @foreach($team->employees as $index => $employee)
                                    <div class="employee-row flex gap-4 items-end pb-4">
                                        <!-- 更新用ID（新規追加の場合は不要） -->
                                        <input type="hidden" name="employees[{{ $index }}][id]" value="{{ $employee->id }}">

                                        <div class="w-1/5">
                                            <x-input-label value="名前" class="text-sm font-semibold" required />
                                            <x-text-input 
                                                type="text" 
                                                name="employees[{{ $index }}][employee_name]" 
                                                class="employee-name mt-1 block w-full py-1.5" 
                                                placeholder="例：山田太郎" 
                                                value="{{ old('employees.' . $index . '.employee_name', $employee->employee_name) }}" />
                                            <x-input-error :messages="$errors->get('employees.' . $index . '.employee_name')" class="mt-2" />
                                        </div>

                                        <div class="w-1/3">
                                            <x-input-label value="メールアドレス" class="text-sm font-semibold" required />
                                            <x-text-input 
                                                type="email" 
                                                name="employees[{{ $index }}][employee_email]" 
                                                class="employee-email mt-1 block w-full py-1.5" 
                                                placeholder="例：yamada@example.com" 
                                                value="{{ old('employees.' . $index . '.employee_email', $employee->email) }}" />
                                            <x-input-error :messages="$errors->get('employees.' . $index . '.employee_email')" class="mt-2" />
                                        </div>

                                        <div class="w-1/8">
                                            <x-input-label value="生年" class="text-sm font-semibold" required />
                                            <select name="employees[{{ $index }}][employee_birth_year]" class="employee-birth-year border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm mt-1 block w-full py-1.5 h-9">
                                                <option value="">選択</option>
                                                @php
                                                    $currentYear = now()->year;
                                                    $startYear = $currentYear - 60;
                                                @endphp
                                                @for ($year = $currentYear; $year >= $startYear; $year--)
                                                    <option value="{{ $year }}" {{ old('employees.' . $index . '.employee_birth_year', $employee->year_of_birth) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                @endfor
                                            </select>
                                            <x-input-error :messages="$errors->get('employees.' . $index . '.employee_birth_year')" class="mt-2" />
                                        </div>

                                        <div class="w-1/6">
                                            <x-input-label value="性別" class="text-sm font-semibold" required />
                                            <div class="mt-1 flex gap-3">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="employees[{{ $index }}][employee_sex]" value="0" class="employee-gender form-radio text-primary h-4 w-4" {{ old('employees.' . $index . '.employee_sex', $employee->sex) == 0 ? 'checked' : '' }} />
                                                    <span class="ml-1 text-sm">男性</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" name="employees[{{ $index }}][employee_sex]" value="1" class="employee-gender form-radio text-primary h-4 w-4" {{ old('employees.' . $index . '.employee_sex', $employee->sex) == 1 ? 'checked' : '' }} />
                                                    <span class="ml-1 text-sm">女性</span>
                                                </label>
                                            </div>
                                        </div>

                                        <button type="button" class="remove-employee delete-question-btn text-orange-default font-semibold border-1.5 border-orange-default rounded-lg px-3 hover:bg-orange-default hover:text-white transition">
                                            削除
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <!-- 従業員情報が存在しない場合は初期状態として1行表示 -->
                                <div class="employee-row flex gap-4 items-end">
                                    <div class="w-1/5">
                                        <x-input-label value="名前" class="text-sm font-semibold" required />
                                        <x-text-input type="text" name="employees[0][employee_name]" class="employee-name mt-1 block w-full py-1.5" placeholder="例：山田太郎" />
                                    </div>

                                    <div class="w-1/3">
                                        <x-input-label value="メールアドレス" class="text-sm font-semibold" required />
                                        <x-text-input type="email" name="employees[0][employee_email]" class="employee-email mt-1 block w-full py-1.5" placeholder="例：yamada@example.com" />
                                    </div>

                                    <div class="w-1/8">
                                        <x-input-label value="生年" class="text-sm font-semibold" required />
                                        <select name="employees[0][employee_birth_year]" class="employee-birth-year border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm mt-1 block w-full py-1.5 h-9">
                                            <option value="">選択</option>
                                            @php
                                                $currentYear = now()->year;
                                                $startYear = $currentYear - 60;
                                            @endphp
                                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endfor
                                        </select>
                                        <x-input-error :messages="$errors->get('employees.0.employee_birth_year')" class="mt-2" />
                                    </div>

                                    <div class="w-1/6">
                                        <x-input-label value="性別" class="text-sm font-semibold" required />
                                        <div class="mt-1 flex gap-3">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="employees[0][employee_sex]" value="0" class="employee-gender form-radio text-primary h-4 w-4" />
                                                <span class="ml-1 text-sm">男性</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="employees[0][employee_sex]" value="1" class="employee-gender form-radio text-primary h-4 w-4" />
                                                <span class="ml-1 text-sm">女性</span>
                                            </label>
                                        </div>
                                    </div>

                                    <button type="button" class="remove-employee delete-question-btn text-orange-default font-semibold border-1.5 border-orange-default rounded-lg px-3 hover:bg-orange-default hover:text-white transition hidden">
                                        削除
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- 追加ボタン -->
                        <div class="flex justify-center">
                            <button type="button" id="add-employee" class="inline-flex items-center bg-blue-300 text-white font-bold px-4 py-1 rounded transition add-question-btn shadow hover:bg-white hover:text-blue-300 hover:border-1.5 hover:border-blue-300">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                追加
                            </button>
                        </div>

                        <!-- 送信ボタン -->
                        <div class="flex justify-center pt-4 text-base">
                            <x-primary-button>
                                更新
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
