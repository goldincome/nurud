<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Admin Dashboard - Nurud</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .bg-brand-blue {
            background-color: #002D72;
        }

        /* Derived from checkout brand-blue */
        .text-brand-orange {
            color: #F58220;
        }

        /* Derived from brand-orange */
        .bg-brand-orange {
            background-color: #F58220;
        }

        .sidebar-link-active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #F58220;
        }
    </style>
    @yield('css')
</head>

<body class="bg-slate-100">

    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-brand-blue text-white flex-shrink-0 flex flex-col transition-all duration-300">
            <div class="p-6 flex items-center gap-3">
                <div class="w-8 h-8 bg-brand-orange rounded-lg flex items-center justify-center">
                    <i class="fas fa-plane text-white"></i>
                </div>
                <span class="text-xl font-bold tracking-tight">Nurud <span class="text-brand-orange">Admin</span></span>
            </div>

            <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'sidebar-link-active text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-lg transition-colors">
                    <i class="fas fa-tachometer-alt w-5"></i> Dashboard
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors">
                    <i class="fas fa-ticket-alt w-5"></i> Bookings
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors">
                    <i class="fas fa-users w-5"></i> Customers
                </a>
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 text-slate-300 hover:bg-white/10 hover:text-white rounded-lg transition-colors">
                    <i class="fas fa-exchange-alt w-5"></i> Transactions
                </a>
                <div class="pt-4 pb-2 text-xs font-bold text-slate-500 uppercase px-4">System</div>
                <a href="{{ route('admin.settings.general') }}"
                    class="{{ request()->routeIs('admin.settings.general') ? 'sidebar-link-active text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-lg transition-colors">
                    <i class="fas fa-cog w-5"></i> General Settings
                </a>
                <a href="{{ route('admin.banks.index') }}"
                    class="{{ request()->routeIs('admin.banks.*') ? 'sidebar-link-active text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-lg transition-colors">
                    <i class="fas fa-university w-5"></i> Banks
                </a>
                <a href="{{ route('admin.markups.index') }}"
                    class="{{ request()->routeIs('admin.markups.*') ? 'sidebar-link-active text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-lg transition-colors">
                    <i class="fas fa-percent w-5"></i> Markup Settings
                </a>
            </nav>

            <div class="p-4 border-t border-white/10">
                <a href="/" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-400 hover:text-white">
                    <i class="fas fa-arrow-left"></i> Main Website
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white h-16 border-b border-slate-200 flex items-center justify-between px-8">
                <div class="flex items-center gap-4">
                    <button class="text-slate-500 lg:hidden"><i class="fas fa-bars"></i></button>
                    <h2 class="font-semibold text-slate-700">@yield('title', 'Admin Dashboard')</h2>
                </div>

                <div class="flex items-center gap-6">
                    <div class="relative">
                        <i class="fas fa-bell text-slate-400 hover:text-brand-blue cursor-pointer"></i>
                        <span
                            class="absolute -top-1 -right-1 bg-brand-orange text-white text-[8px] rounded-full w-4 h-4 flex items-center justify-center">3</span>
                    </div>
                    <div class="flex items-center gap-3 border-l pl-6 border-slate-200">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-slate-800">Admin User</p>
                            <p class="text-[10px] text-slate-500">Super Administrator</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=Admin+User&background=002D72&color=fff"
                            class="w-10 h-10 rounded-full border border-slate-200" alt="Profile">
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('js')
</body>

</html>