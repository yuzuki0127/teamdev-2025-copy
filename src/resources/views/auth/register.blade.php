<x-guest-layout>
    <div class="min-h-screen bg-white flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-lg rounded-lg">
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('images/logo.svg') }}" class="w-50 h-20 object-cover">
                <h2 class="mt-4 text-2xl font-bold text-gray-900">新規登録</h2>
            </div>

            <div class="flex justify-center mb-6">
                <div class="bg-blue-default text-white font-bold py-2 w-full text-left">
                    <span class="pl-2">アカウント登録</span>
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('名前')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('メールアドレス')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('パスワード')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('パスワード（確認用）')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex justify-center mt-6">
                    <x-primary-button>
                        {{ __('登録する') }}
                    </x-primary-button>
                </div>

                <div class="text-center mt-4">
                    <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        {{ __('アカウントをお持ちの方はこちら') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
