<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ทะเบียนออนไลน์ (RGO)</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo2.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
</head>

<body class="font-sans antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen" style="min-height: 80vh;">
        {{-- <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false"
            class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div> --}}
        {{-- 1. Desktop Sidebar --}}
        {{-- แสดงเฉพาะจอใหญ่ (lg) และซ่อนในจอเล็ก --}}
        <div class="hidden lg:flex lg:flex-shrink-0">
            @include('layouts.sidebar')
        </div>
        {{-- @include('layouts.sidebar') --}}
        <div class="flex-1 flex flex-col overflow-hidden ">
            @include('layouts.header')
            <main class="flex-1 overflow-y-auto" >
                <div class="content min-h-full p-4 sm:p-6 lg:p-8" style="min-height: 90vh;">
                    {{ $slot }}
                </div>
            </main>
        </div>

        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-blue-500 opacity-50 transition-opacity lg:hidden" x-cloak></div>
        <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto bg-blue-800 lg:hidden" x-cloak>
            @include('layouts.mobile-menu')
        </div>

    </div>
</body>

<style>
    .flatpickr-calendar {
        z-index: 2000 !important;
    }

    .aa {
        background-color: #f8f8f8;
    }
</style>

</html>
