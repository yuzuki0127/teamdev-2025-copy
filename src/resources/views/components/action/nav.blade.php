<nav class="bg-blue-default text-white px-6 pt-4 pb-1 mb-8 fixed w-full z-10 shadow-xl action-nav">
    <div class="max-w-screen-lg mx-auto flex items-center space-x-4 ml-25">
        @unless (request()->routeIs('action.tasks'))
            <div class="flex items-center space-x-1">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 fill-current {{ request()->routeIs('action.create') ? 'block' : 'hidden' }}"
                    viewBox="0 0 24 24">
                    <path
                        d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM12 11.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
                </svg>
                <span class="font-bold {{ request()->routeIs('action.create') ? 'border-b-2 border-white' : '' }}">
                    <a href="{{ url('/action/create') }}">案を採用する</a>
                </span>
            </div>
        <span class="text-white text-sm">≫</span>
        @endunless
        <div class="flex items-center space-x-1">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 fill-current {{ request()->routeIs('action.tasks') ? 'block' : 'hidden' }}"
                viewBox="0 0 24 24">
                <path
                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM12 11.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
            </svg>
            <span
                class="text-white font-bold {{ request()->routeIs('action.tasks') ? 'border-b-2 border-white' : '' }}">
                <a href="{{ url('/action/tasks') }}">タスク管理</a>
            </span>
        </div>
</nav>
