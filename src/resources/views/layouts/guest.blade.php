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
</head>

<body class="font-sans text-gray-900 text-sm antialiased bg-cream-default">

    <!-- status メッセージの表示 -->
    @if (session('status'))
        <div class="my-5 p-4 bg-green-100 text-green-800 rounded">
            {{ session('status') }}
        </div>
    @endif

    <!-- error メッセージの表示 -->
    @if (session('error'))
        <div class="my-5 p-4 bg-red-100 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif
    {{ $slot }}
</body>

</html>
