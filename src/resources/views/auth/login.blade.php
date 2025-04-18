<x-guest-layout>
    <div class="min-h-screen bg-white flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-lg rounded-lg">
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('images/logo.svg') }}" class="w-50 h-20 object-cover">
                <h2 class="text-2xl font-bold text-gray-900">ログイン</h2>
            </div>

            <div class="flex justify-center">
                <div class="bg-blue-default text-white font-bold py-2 w-full text-left">
                    <span class="pl-2">ログイン</span>
                </div>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('メールアドレス')" />
                    <x-text-input
                        id="email"
                        class="block mt-1 w-full"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('パスワード')" />
                    <x-text-input
                        id="password"
                        class="block mt-1 w-full"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Login Button -->
                <div class="flex justify-center mt-6">
                    <x-primary-button>
                        {{ __('ログインする') }}
                    </x-primary-button>
                </div>

                <!-- Remember Me -->
                <div class="mt-4 text-center">
                    <label for="remember_me" class="inline-flex items-center">
                        <input
                            id="remember_me"
                            type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            name="remember"
                        />
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <!-- Forgot Password & Register Links -->
                <div class="text-center mt-4">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                            {{ __('パスワードを忘れた方はこちら') }}
                        </a>
                    @endif
                    <div class="mt-2">
                        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">
                            {{ __('新規登録はこちら') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
