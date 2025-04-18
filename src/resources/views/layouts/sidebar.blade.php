<aside class="w-51.25 bg-white h-screen flex flex-col items-start fixed z-50">
    <div class="w-full border-blue-default mt-12.5 mb-12.5 pl-6">
        <!-- プロフィール画像 -->
        <div class="w-50 h-20 bg-white -ml-7.5">
            <img src="{{ asset('images/logo.svg') }}" class="w-full h-full object-cover">
        </div>
        
        <!-- メニュー -->
        <nav class="w-full">
            <!-- ダッシュボード -->
            <div class="w-45 h-10 border-b-2 border-dashed border-blue-default group flex items-center mb-4 
                {{ Request::is('dashboard') ? 'bg-blue-default' : 'bg-white hover:bg-blue-default hover:bg-opacity-50' }}">
                <a href="{{ url('dashboard') }}" class="block w-full pl-1 transition-all duration-300 font-bold 
                    {{ Request::is('dashboard') ? 'text-white' : 'text-blue-default hover:text-white' }}">
                    ダッシュボード
                </a>
            </div>

            <!-- アンケート -->
            <div class="w-45 h-10 group flex items-center mb-4 
                {{ Request::is('survey/create') ? 'bg-blue-default' : 'bg-white hover:bg-blue-default hover:bg-opacity-50' }}">
                <a href="{{ url('survey/create') }}" class="block w-full pl-1 transition-all duration-300 font-bold 
                    {{ Request::is('survey/create') ? 'text-white' : 'text-blue-default hover:text-white' }}">
                    アンケート
                </a>
            </div>

            <!-- 分析の結果と提案 -->
            <div class="w-45 h-10 group flex items-center mb-4 
                {{ Request::is('analysis') || Request::is('analysis/detail') || Request::is('analysis/past-plan') ? 'bg-blue-default' : 'bg-white hover:bg-blue-default hover:bg-opacity-50' }}">
                <a href="{{ url('analysis') }}" class="block w-full pl-1 transition-all duration-300 font-bold 
                    {{ Request::is('analysis') || Request::is('analysis/detail') || Request::is('analysis/past-plan') ? 'text-white' : 'text-blue-default hover:text-white' }}">
                    分析の結果と提案
                </a>
            </div>

            <!-- タスク管理 -->
            <div class="w-45 h-10 border-b-2 border-dashed border-blue-default group flex items-center mb-4 
                {{ Request::is('action/tasks')|| Request::is('action/create') ? 'bg-blue-default' : 'bg-white hover:bg-blue-default hover:bg-opacity-50' }}">
                <a href="{{ url('action/tasks') }}" class="block w-full pl-1 transition-all duration-300 font-bold 
                    {{ Request::is('action/tasks')|| Request::is('action/create') ? 'text-white' : 'text-blue-default hover:text-white' }}">
                    タスク管理
                </a>
            </div>

            <!-- ポートフォリオ -->
            <div class="w-45 h-10 group flex items-center mb-4 
                {{ Request::is('portfolio') ? 'bg-blue-default' : 'bg-white hover:bg-blue-default hover:bg-opacity-50' }}">
                <a href="{{ url('portfolio') }}" class="block w-full pl-1 transition-all duration-300 text-sm font-bold 
                    {{ Request::is('portfolio') ? 'text-white' : 'text-blue-default hover:text-white' }}">
                    ポートフォリオ
                </a>
            </div>

            <!-- 企業情報 -->
            <div class="w-45 h-10 border-blue-default group flex items-center mb-4 
                {{ Request::is('dashboard/company-edit') ? 'bg-blue-default' : 'bg-white hover:bg-blue-default hover:bg-opacity-50' }}">
                <a href="{{ url('dashboard/company-edit') }}" class="block w-full pl-1 transition-all duration-300 text-sm font-bold 
                    {{ Request::is('dashboard/company-edit') ? 'text-white' : 'text-blue-default hover:text-white' }}">
                    企業情報
                </a>
            </div>

            <!-- ログアウト -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full pl-1 text-blue-default transition-all duration-300 text-sm font-bold text-start">
                    ログアウト
                </button>
            </form>
        </nav>
    </div>
</aside>



{{-- <aside class="w-51.25 bg-white h-screen flex flex-col items-start fixed z-50">
    <div class="w-full border-blue-default mt-12.5 mb-12.5 pl-6">
        <!-- プロフィール画像 -->
        <div class="w-50 h-20 bg-white -ml-7.5">
            <img src="{{ asset('images/logo.svg') }}" class="w-full h-full object-cover">
        </div>
        <!-- メニュー -->
        <nav class="w-full">
            <div class="w-45 h-10 border-b-2 border-dashed border-blue-default group flex items-center mb-4 {{ Request::is('dashboard') ? 'bg-blue-default ' : 'bg-white hover:bg-blue-default hover:bg-opacity-50' }}">
                <a href="{{ url('dashboard') }}" class="block w-full pl-1 transition-all duration-300 font-bold {{ Request::is('dashboard') ? 'text-white ' : 'text-blue-default hover:text-white' }}">
                    ダッシュボード
                </a>
            </div>
            <div class="w-45 h-10 group flex items-center mb-4 {{ Request::is('survey/create') ? 'bg-blue-default ' : 'bg-white hover:bg-blue-default hover:bg-opacity-50 ' }}">
                <a href="{{ url('survey/create') }}" class="block w-full pl-1 transition-all duration-300 font-bold {{ Request::is('survey/create') ? 'text-white ' : 'text-blue-default hover:text-white' }}">
                    アンケート
                </a>
            </div>
            <div class="w-45 h-10 group flex items-center mb-4 {{ Request::is('analysis') ? 'bg-blue-default ' : 'bg-white hover:bg-blue-default hover:bg-opacity-50' }}">
                <a href="{{ url('analysis','analysis/detail','analysis/past-plan') }}" class="block w-full pl-1 transition-all duration-300 font-bold {{ Request::is('analysis') ? 'text-white ' : 'text-blue-default hover:text-white' }}">
                    分析の結果と提案
                </a>
            </div>
            <div class="w-45 h-10 border-b-2 border-dashed border-blue-default group flex items-center mb-4 {{ Request::is('action/tasks') ? 'bg-blue-default ' : 'bg-white hover:bg-blue-default hover:bg-opacity-50' }}">
                <a href="{{ url('action/create','action/tasks') }}" class="block w-full pl-1 transition-all duration-300 font-bold {{ Request::is('action/tasks') ? 'text-white ' : 'text-blue-default hover:text-white' }}">
                    タスク管理
                </a>
            </div>
            <div class="w-45 h-10 group flex items-center mb-4 {{ Request::is('portfolio') ? 'bg-blue-default ' : 'bg-white hover:bg-blue-default hover:bg-opacity-50' }}">
                <a href="{{ url('portfolio') }}" class="block w-full pl-1 transition-all duration-300 text-sm font-bold {{ Request::is('portfolio') ? 'text-white ' : 'text-blue-default hover:text-white' }}">
                    ポートフォリオ
                </a>
            </div>

            <!-- 企業情報 -->
            <div class="w-45 h-10 border-blue-default group flex items-center mb-4 {{ Request::is('dashboard/company-edit') ? 'bg-blue-default ' : 'bg-white hover:bg-blue-default hover:bg-opacity-50' }}">
                <a href="{{ url('dashboard/company-edit') }}" class="block w-full pl-1 transition-all duration-300 text-sm font-bold {{ Request::is('dashboard/company-edit') ? 'text-white ' : 'text-blue-default hover:text-white' }}">
                    企業情報
                </a>
            </div>

            <!-- ログアウト -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full pl-1 text-blue-default transition-all duration-300 text-sm font-bold text-start">ログアウト</button>
            </form>
        </nav>
    </div>
</aside> --}}
