<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'RFC Vote' }} {{ app()->isProduction() ? '' : ' (local)' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    @include('feed::links')
    @stack('styles')
    @stack('scripts')

    {{ app(\App\Support\Meta::class)->render() }}

    @stack('meta')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

@php
    $user = auth()->user();
@endphp

<nav class="bg-main bg-gradient-to-r from-main to-main-light z-10 p-4">
    <div
        class="container flex justify-between text-white gap-4 items-center m-auto relative px-2"
        x-data="{ open: false }"
    >
        <div class="text-lg md:text-xl font-bold relative z-20">
            <a href="/">
                RFC Vote
                <span>{{ app()->isProduction() ? '' : ' (local)' }}</span>
            </a>
        </div>

        <x-navbar.mobile-menu-trigger />

        {{-- Overlay --}}
        <div
            x-cloak
            x-show="open"
            @click="open = false"
            class="bg-slate-900/60 fixed inset-0 z-10"
        ></div>

        <div
            class="md:flex justify-end items-center md:gap-6 font-bold text-sm md:text-md inset-x-2 top-14 z-10"
            :class="open ? 'flex absolute bg-white text-gray-700 flex-col rounded-xl shadow-lg text-[1.1em] py-8 px-4' : 'hidden gap-4'"
            x-cloak
        >
            <x-navbar.link
                href="{{ action(App\Http\Controllers\HomeController::class) }}"
                :isActive="request()->is('/')"
            >
                Open RFCs
            </x-navbar.link>

            <x-navbar.link
                href="{{ action(App\Http\Controllers\AboutController::class) }}"
                :isActive="request()->is('about')"
            >
                About
            </x-navbar.link>

            @if($user)
                @if($user->is_admin)
                    <x-navbar.link
                        href="{{ action(App\Http\Controllers\RfcAdminController::class) }}"
                        :isActive="request()->is('admin/*')"

                    >
                        Admin
                        @if($pendingVerificationRequests)
                            <span>({{ $pendingVerificationRequests }})</span>
                        @endif
                    </x-navbar.link>
                @endif

                <x-navbar.link
                    href="{{ action(\App\Http\Controllers\MessagesController::class) }}"
                    :isActive="request()->is('messages')"
                >
                    <span class="flex gap-1">
                        <x-icons.inbox class="w-5 h-5" /> <span class="md:hidden">Messages (</span>{{ $user->unread_message_count >= 1 ? $user->unread_message_count : '' }}<span class="md:hidden">)</span>
                    </span>
                </x-navbar.link>

                <x-profile.user-menu.menu :user="$user" />
            @else
                <x-navbar.link
                    href="{{ action(App\Http\Controllers\LoginController::class) }}"
                    :isActive="request()->is('login')"
                >
                    Login
                </x-navbar.link>

                <x-navbar.link
                    href="{{ action(App\Http\Controllers\RegisterController::class) }}"
                    :isActive="request()->is('register')"
                >
                    Register
                </x-navbar.link>
            @endif
        </div>
    </div>
</nav>

<div class="flex-1">
    @if(flash()->message)
        <div
            class="container mx-auto px-4 mt-4 md:mt-12 max-w-[1200px] mb-8"
        >
            <div class="p-4 md:px-8 bg-blue-200 mb-4 md:mb-8 rounded-md md:rounded-full text-blue-900 font-bold">
                {{ flash()->message }}
            </div>
        </div>
    @endif

    {{ $slot }}
</div>

@if(isset($showToTopArrow) && $showToTopArrow === true)
    <div class="sticky flex self-end justify-end bottom-6 right-6"
         x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY; updateVisibility() })"
         x-data="{ isVisible: false, scrolled: 0, updateVisibility() { this.isVisible = (this.scrolled / (document.documentElement.scrollHeight - window.innerHeight)) >= 0.5; } }"
         x-show="isVisible"
    >
        <button
            x-show="isVisible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"

            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            onclick="window.scrollTo({top: 0});"
            class="rounded-full bg-purple-600 p-4 text-white shadow-md hover:bg-purple-700 duration-700 hover:-translate-y-3 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg active:bg-purple-800 active:shadow-lg"
        >
            <x-icons.arrow-double-up/>
        </button>
    </div>
@endif

<x-footer />

@livewireScripts
</body>
</html>
