<nav class="bg-blue-default text-white px-6 pt-4 pb-1 mb-8 fixed w-full z-10 shadow-xl">
    <div class="max-w-screen-lg mx-auto flex items-center space-x-4 ml-25">
        <div class="flex items-center space-x-1">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 fill-current {{ request()->routeIs('analysis.index') ? 'block' : 'hidden' }}"
                viewBox="0 0 24 24">
                <path
                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM12 11.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
            </svg>
            <span class="font-bold {{ request()->routeIs('analysis.index') ? 'border-b-2 border-white' : '' }}">
                <a href="{{ url('/analysis') }}">分析と提案</a>
            </span>
        </div>
        <span class="text-white text-sm">≫</span>
        <div class="flex items-center space-x-1">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 fill-current {{ request()->routeIs('analysis.detail') ? 'block' : 'hidden' }}"
                viewBox="0 0 24 24">
                <path
                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM12 11.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
            </svg>
            <span
                class="text-white font-bold {{ request()->routeIs('analysis.detail') ? 'border-b-2 border-white' : '' }}">
                <a href="{{ url('/analysis/detail') }}">アンケートの詳細</a>
            </span>
        </div>
        <div class="flex items-center space-x-1">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 fill-current {{ request()->routeIs('planning.past') ? 'block' : 'hidden' }}"
                viewBox="0 0 24 24">
                <path
                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM12 11.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
            </svg>
            <span
                class="text-white font-bold {{ request()->routeIs('planning.past') ? 'border-b-2 border-white' : '' }}">
                <a href="{{ url('/analysis/past-plan') }}">過去の提案</a>
            </span>
        </div>
</nav>
