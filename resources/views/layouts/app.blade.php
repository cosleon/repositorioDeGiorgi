<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ColeccionismoTCG') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .poke-header {
                background: linear-gradient(90deg, #1f3c88, #3949ab);
                color: #fff;
                padding: 1.5rem;
                text-align: center;
            }

            .poke-header h1 {
                font-size: 2rem;
                font-weight: bold;
                margin-bottom: 0.5rem;
            }

            .poke-nav {
                display: flex;
                justify-content: center;
                gap: 1.5rem;
                margin-top: 0.5rem;
            }

            .poke-nav a {
                color: #ffcb05;
                font-weight: 600;
                text-decoration: none;
            }

            .poke-nav a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen">
    <div class="poke-header relative px-4 py-3 bg-blue-900 text-white">

            <!-- Menú de usuario (responsive con hamburguesa) -->
        <div x-data="{ open: false }" class="sm:hidden absolute right-4 top-4">
            <!-- Botón hamburguesa -->
            <button @click="open = !open" class="text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Menú desplegable -->
            <div x-show="open" @click.away="open = false"
                class="mt-2 py-2 w-40 bg-white rounded shadow-lg text-black z-50 absolute right-0">
                @auth
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left px-4 py-2 text-blue-700 font-semibold underline hover:bg-gray-100"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            Cerrar sesión
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-100">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="block px-4 py-2 hover:bg-gray-100">Registrarse</a>
                @endauth
            </div>
        </div>

        <div class="inline-block align-middle">
            <h1 class="text-2xl font-bold">ColeccionismoTCG</h1>
            <nav class="poke-nav inline-flex space-x-4 mt-1">
                <a href="{{ route('cards.index') }}" class="hover:underline">Cartas</a>
                <a href="{{ route('sobre-mi') }}" class="hover:underline">Sobre mí</a>

                @auth
                    @if (Auth::user()->id === 3)
                        <a href="{{ route('cards.listarPromos') }}" class="hover:underline">Promos</a>
                    @endif
                @endauth
            </nav>

        </div>
        <div class="hidden sm:flex sm:items-center absolute right-4 top-1/2 -translate-y-1/2">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-800 hover:bg-red-700 transition">
                        <div>{{ Auth::check() ? Auth::user()->name : 'Invitado' }}</div>
                        <div class="ml-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    @auth
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Cerrar sesión') }}
                            </x-dropdown-link>
                        </form>
                    @else
                        <x-dropdown-link :href="route('login')">
                            {{ __('Iniciar sesión') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('register')">
                            {{ __('Registrarse') }}
                        </x-dropdown-link>
                    @endauth
                </x-slot>
            </x-dropdown>
        </div>
    </div>
    @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <main class="px-4 sm:px-6 lg:px-8 overflow-x-auto">
        @yield('content')
    </main>
</div>
    @stack('scripts')
    </body>
</html>
