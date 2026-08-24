<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login HR Portal - {{ $companySetting->company_name ?? 'Assessment Center' }}</title>
    @if(!empty($companySetting->favicon_path))
        <link rel="icon" href="{{ asset($companySetting->favicon_path) }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --color-primary: {{ $companySetting->primary_color ?? '#2563eb' }};
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 font-sans text-slate-800 antialiased">
    <div class="max-w-md w-full">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            @if(!empty($companySetting->logo_path))
                <img src="{{ asset($companySetting->logo_path) }}" alt="{{ $companySetting->company_name }}" class="h-12 w-auto mx-auto mb-3 object-contain">
            @else
                <div class="w-12 h-12 rounded-xl bg-blue-600 text-white font-extrabold text-2xl flex items-center justify-center mx-auto mb-3 shadow-lg" style="background-color: var(--color-primary);">
                    {{ substr($companySetting->company_name ?? 'A', 0, 1) }}
                </div>
            @endif
            <h1 class="text-2xl font-black text-white tracking-tight">{{ $companySetting->company_name ?? 'Assessment Center' }}</h1>
            <p class="text-xs text-slate-400 mt-1">HR & Recruitment Assessment Portal</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl p-8 shadow-2xl border border-slate-800/20">
            <h2 class="text-lg font-bold text-slate-900 mb-1">Masuk ke Dashboard</h2>
            <p class="text-xs text-slate-500 mb-6">Gunakan kredensial akun HR Anda untuk mengakses hasil tes kandidat.</p>

            @if(session('info'))
                <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded text-xs text-blue-800 mb-4">
                    {{ session('info') }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Email HR</label>
                    <input type="email" name="email" id="email" value="{{ old('email', 'hr@company.com') }}" required
                           class="w-full px-3.5 py-2.5 text-sm rounded-lg border @error('email') border-red-500 bg-red-50 @else border-slate-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" required value="password123"
                           class="w-full px-3.5 py-2.5 text-sm rounded-lg border @error('password') border-red-500 bg-red-50 @else border-slate-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center space-x-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-blue-600 focus:ring-blue-500">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-2.5 px-4 text-white font-bold text-sm rounded-lg shadow-md hover:opacity-90 transition duration-150"
                            style="background-color: var(--color-primary);">
                        Masuk Dashboard
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-100 text-center text-[11px] text-slate-400">
                Default login: <strong>hr@company.com</strong> / <strong>password123</strong>
            </div>
        </div>
    </div>
</body>
</html>
