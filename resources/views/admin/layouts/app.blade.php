<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - {{ $companySetting->company_name ?? 'Assessment Center' }}</title>
    @if(!empty($companySetting->favicon_path))
        <link rel="icon" href="{{ asset($companySetting->favicon_path) }}">
    @endif

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: 'var(--color-primary)',
                        'primary-dark': '#1d4ed8',
                        secondary: 'var(--color-secondary)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <!-- ApexCharts CDN for DISC Visualization -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Custom CSS Variables for Dynamic Branding -->
    <style>
        :root {
            --color-primary: {{ $companySetting->primary_color ?? '#2563eb' }};
            --color-secondary: {{ $companySetting->secondary_color ?? '#475569' }};
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full flex flex-col font-sans text-slate-800 antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex h-full">

        <!-- Sidebar for Desktop -->
        <aside class="hidden md:flex md:flex-col md:w-64 bg-slate-900 text-slate-300 border-r border-slate-800">
            <!-- Sidebar Header / Logo -->
            <div class="h-16 flex items-center px-6 border-b border-slate-800 space-x-3">
                @if(!empty($companySetting->logo_path))
                    <img src="{{ asset($companySetting->logo_path) }}" alt="{{ $companySetting->company_name }}" class="h-8 w-auto object-contain brightness-200">
                @else
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-base shadow" style="background-color: var(--color-primary);">
                        {{ substr($companySetting->company_name ?? 'A', 0, 1) }}
                    </div>
                @endif
                <div class="overflow-hidden">
                    <h2 class="text-sm font-bold text-white truncate">{{ $companySetting->company_name ?? 'Assessment Center' }}</h2>
                    <p class="text-[10px] text-slate-400 font-medium">HR Portal</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                   style="{{ request()->routeIs('admin.dashboard') ? 'background-color: var(--color-primary);' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.submissions.index') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('admin.submissions.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                   style="{{ request()->routeIs('admin.submissions.*') ? 'background-color: var(--color-primary);' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span>Hasil Kandidat</span>
                </a>

                <a href="{{ route('admin.assessments.index') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('admin.assessments.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                   style="{{ request()->routeIs('admin.assessments.*') ? 'background-color: var(--color-primary);' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span>Kelola Assessment</span>
                </a>

                <a href="{{ route('admin.branding.index') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('admin.branding.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                   style="{{ request()->routeIs('admin.branding.*') ? 'background-color: var(--color-primary);' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                    <span>Branding Perusahaan</span>
                </a>
            </nav>

            <!-- Current User & Logout -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                <div class="flex items-center justify-between">
                    <div class="truncate mr-2">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'HR Admin' }}</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email ?? 'hr@company.com' }}</p>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" title="Logout" class="p-2 rounded-lg text-slate-400 hover:text-red-400 hover:bg-slate-800 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Topbar (Mobile & Actions) -->
            <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-4 sm:px-6 z-10">
                <div class="flex items-center space-x-3">
                    <button type="button" @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-lg font-bold text-slate-900">@yield('page_title', 'Dashboard')</h1>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('candidate.register', 'disc-behavioral-assessment') }}" target="_blank"
                       class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 flex items-center space-x-1.5 shadow-sm">
                        <span>Lihat Link Publik</span>
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </div>
            </header>

            <!-- Flash Messages -->
            <div class="px-4 sm:px-6 pt-4">
                @if(session('success'))
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm text-sm text-emerald-800 flex items-center justify-between">
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm text-sm text-red-800 flex items-center justify-between">
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
            </div>

            <!-- Page Body -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Drawer Sidebar -->
    <div x-show="sidebarOpen" x-cloak class="md:hidden fixed inset-0 z-50 flex">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="sidebarOpen = false"></div>
        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-slate-900 text-slate-300 p-4">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                <span class="font-bold text-white text-base">{{ $companySetting->company_name ?? 'HR Portal' }}</span>
                <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <nav class="mt-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold hover:bg-slate-800">Dashboard</a>
                <a href="{{ route('admin.submissions.index') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold hover:bg-slate-800">Hasil Kandidat</a>
                <a href="{{ route('admin.assessments.index') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold hover:bg-slate-800">Kelola Assessment</a>
                <a href="{{ route('admin.branding.index') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold hover:bg-slate-800">Branding Perusahaan</a>
            </nav>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
