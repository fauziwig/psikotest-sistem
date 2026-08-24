<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Online Behavioral Assessment') - {{ $companySetting->company_name ?? 'Assessment Center' }}</title>
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
                        'primary-dark': 'var(--color-primary-dark)',
                        secondary: 'var(--color-secondary)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <!-- Custom CSS Variables for Dynamic Company Branding -->
    <style>
        :root {
            --color-primary: {{ $companySetting->primary_color ?? '#2563eb' }};
            --color-primary-dark: #1d4ed8;
            --color-secondary: {{ $companySetting->secondary_color ?? '#475569' }};
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full flex flex-col font-sans text-slate-800 antialiased">
    <!-- Header / Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                @if(!empty($companySetting->logo_path))
                    <img src="{{ asset($companySetting->logo_path) }}" alt="{{ $companySetting->company_name }}" class="h-9 w-auto object-contain">
                @else
                    <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-sm" style="background-color: var(--color-primary);">
                        {{ substr($companySetting->company_name ?? 'A', 0, 1) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-base font-bold text-slate-900 leading-tight">{{ $companySetting->company_name ?? 'Assessment Center' }}</h1>
                    <p class="text-xs text-slate-500">Recruitment & Talent Assessment</p>
                </div>
            </div>

            @yield('header_actions')
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-400 mt-auto">
        <div class="max-w-5xl mx-auto px-4">
            &copy; {{ date('Y') }} {{ $companySetting->company_name ?? 'Assessment Center' }}. Hak cipta dilindungi undang-undang.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
