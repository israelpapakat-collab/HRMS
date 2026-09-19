<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - HR Management</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-surface-50">
        <div class="flex min-h-screen">
            @include('layouts.sidebar')

            <div class="flex-1 flex flex-col">
                <header class="bg-white/80 backdrop-blur-sm border-b border-surface-200 sticky top-0 z-30">
                    <div class="px-8 py-4 flex items-center justify-between">
                        <div>
                            @if (isset($header))
                                <h1 class="text-2xl font-bold text-surface-900">{{ $header }}</h1>
                            @endif
                            <p class="text-sm text-surface-500 mt-0.5">
                                {{ now()->format('l, F j, Y') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('notifications.index') }}" class="relative p-2 text-surface-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200">
                                <i class="fa-regular fa-bell text-lg"></i>
                                @php
                                    $unread = \App\Models\AppNotification::where('user_id', Auth::id())->where('is_read', false)->count();
                                @endphp
                                @if($unread > 0)
                                <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $unread > 9 ? '9+' : $unread }}</span>
                                @endif
                            </a>
                            <div class="flex items-center gap-3 pl-4 border-l border-surface-200">
                                <div class="w-9 h-9 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-sm">
                                    {{ substr(Auth::user()->name, 0, 2) }}
                                </div>
                                <div class="text-sm">
                                    <p class="font-semibold text-surface-800">{{ Auth::user()->name }}</p>
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-surface-400 hover:text-rose-500 transition-colors">Sign out</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="flex-1 p-8">
                    @if (session('success'))
                        <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3.5 rounded-xl shadow-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                            <p class="text-sm font-medium flex-1">{{ session('success') }}</p>
                            <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-700 px-5 py-3.5 rounded-xl shadow-sm">
                            <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                            <p class="text-sm font-medium flex-1">{{ session('error') }}</p>
                        </div>
                    @endif

                    <div class="animate-fade-in">
                        {{ $slot }}
                    </div>
                </main>

                <footer class="bg-white border-t border-surface-200 px-8 py-4">
                    <p class="text-sm text-surface-400 text-center">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }} HR Management System. All rights reserved.
                    </p>
                </footer>
            </div>
        </div>
    </body>
</html>
