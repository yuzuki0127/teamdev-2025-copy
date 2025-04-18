<x-guest-layout>
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="w-250 mx-auto">
                    <h2 class="text-2xl font-bold text-primary mb-6 text-center">企業情報登録</h2>

                    <form method="POST" action="{{ route('company.first-register.store') }}" class="space-y-6">
                        @csrf

                        <!-- 企業情報 -->
                        <div class="pb-6">
                            <div class="flex justify-center mb-7">
                                <div class="bg-blue-default text-white font-bold py-2 w-full text-left">
                                    <span class="pl-2">企業情報</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-6">
                                <!-- 会社名 -->
                                <div>
                                    <x-input-label for="company_name" value="会社名" required />
                                    <x-text-input required id="company_name" name="company_name" type="text"
                                        class="mt-1 block w-full" placeholder="例：株式会社POSSE" />
                                    <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                                </div>

                                <!-- 業種 -->
                                <div>
                                    <x-input-label for="industry" value="業種" required />
                                    <select required id="industry" name="industry" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">選択してください</option>
                                        @foreach($industries as $industry)
                                            <option value="{{ $industry->id }}">{{ $industry->industry_name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('industry')" class="mt-2" />
                                </div>

                                <!-- 会社区分 -->
                                <div>
                                    <x-input-label for="company_category" value="会社区分" required />
                                    <select required id="company_category" name="company_category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">選択してください</option>
                                        @foreach($companyCategories as $companyCategory)
                                            <option value="{{ $companyCategory->id }}">{{ $companyCategory->company_category_name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('company_category')" class="mt-2" />
                                </div>
                                
                                <!-- 部署名 -->
                                <div>
                                    <x-input-label for="department" value="部署名" required />
                                    <x-text-input required id="department" name="department" type="text"
                                        class="mt-1 block w-full" placeholder="例：人事部" />
                                    <x-input-error :messages="$errors->get('department')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- 従業員情報 -->
                        <div id="employee-list">
                            <div class="flex justify-center mb-7">
                                <div class="bg-blue-default text-white font-bold py-2 w-full text-left">
                                    <span class="pl-2">従業員情報（後からでも追加できます）</span>
                                </div>
                            </div>
                            <div class="employee-row flex gap-4 items-end">
                                <!-- 名前 -->
                                <div class="w-1/6">
                                    <x-input-label for="employee_name_0" value="名前" class="text-sm font-semibold"
                                        required />
                                    <x-text-input required id="employee_name_0" name="employees[0][employee_name]" type="text"
                                        class="employee-name mt-1 block w-full py-1.5" placeholder="例：山田太郎" />
                                    <x-input-error :messages="$errors->get('employees.0.employee_name')" class="mt-2" />
                                </div>

                                <!-- メールアドレス -->
                                <div class="w-1/2">
                                    <x-input-label for="employee_email_0" value="メールアドレス" class="text-sm font-semibold"
                                        required />
                                    <x-text-input required id="employee_email_0" name="employees[0][employee_email]"
                                        type="email" class="employee-email mt-1 block w-full py-1.5"
                                        placeholder="例：yamada@example.com" />
                                    <x-input-error :messages="$errors->get('employees.0.employee_email')" class="mt-2" />
                                </div>

                                <!-- 生年 -->
                                <div class="w-1/8">
                                    <x-input-label for="employee_birth_year_0" value="生年"
                                        class="text-sm font-semibold" required />
                                    <select required id="employee_birth_year_0" name="employees[0][employee_birth_year]"
                                        class="employee-birth-year border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm mt-1 block w-full py-1.5 h-9">
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

                                <!-- 性別 -->
                                <div class="w-1/6">
                                    <x-input-label value="性別" class="text-sm font-semibold" required />
                                    <div class="mt-1 flex gap-3">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="employees[0][employee_sex]" value=0
                                                class="employee-gender form-radio text-primary h-4 w-4" />
                                            <span class="ml-1 text-sm">男性</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="employees[0][employee_sex]" value=1
                                                class="employee-gender form-radio text-primary h-4 w-4" />
                                            <span class="ml-1 text-sm">女性</span>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('employees.0.employee_sex')" class="mt-2" />
                                </div>

                                <!-- nits 削除ボタンをimgフォルダに移動 -->
                                <button type="button"
                                    class="remove-employee ml-4 inline-flex items-center px-3 py-2 text-sm text-red-500 hover:text-red-700">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18 12H6" />
                                    </svg>
                                    削除
                                </button>
                            </div>
                        </div>

                        <!-- 追加ボタン -->
                        <div class="flex justify-center mt-4">
                            <button type="button" id="add-employee"
                                class="inline-flex items-center px-4 py-2 text-sm text-primary hover:text-primary-dark">
                                <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                追加
                            </button>
                        </div>

                        <!-- 送信ボタン -->
                        <div class="flex justify-center pt-4">
                            <x-primary-button>完了</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
