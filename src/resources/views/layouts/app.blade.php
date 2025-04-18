<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ConPath') }}</title>
    <link rel="icon" href="{{ asset('images/icon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/x-icon">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="font-sans antialiased text-black-default">

    <div class="flex min-h-screen bg-white">
        <!-- サイドバー -->
        @include('layouts.sidebar')

        <!-- メインコンテンツ -->
        <div class="flex-1 ml-51.25 bg-cream-default">
            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow w-full fixed ml-51.25 top-0 z-40">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
                <!-- ヘッダー分のスペースを確保 -->
                {{-- <div class="h-16"></div> --}}
            @endif

            <!-- Page Content -->
            <!-- status メッセージの表示 -->
            @if (session('status'))
                <div class="p-4 bg-green-100 text-green-800 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <!-- error メッセージの表示 -->
            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-800 rounded">
                    {{ session('error') }}
                </div>
            @endif
            <main class="text-gray-700">
                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
