<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <script src="{{ asset('css/fontbunny') }}"></script>
        {{-- <link rel="preconnect" href="https://fonts.bunny.net"> --}}
        {{-- <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}
        <link rel="icon" href="{{asset('svg/2sViAPwvYhpvEIpWwI8S6SJgc7S.svg')}}">
        <!-- tailwind -->
        <script src="{{ asset('css/min') }}"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div>
            <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
                <img class="absolute z-0 w-screen h-screen bg-no-repeat" src="{{asset('img/Picsart_25-02-05_01-02-51-216.png')}}"  />
                <div>
                    <a href="/" wire:navigate>
                        <x-application-logo />
                    </a>
                </div>

                <div class="w-full z-10 sm:max-w-md mt-6 dark:bg-gray-800 overflow-hidden sm:rounded-lg bg-white/30 backdrop-blur-md shadow-lg shadow-stone-400/50 p-8 ">
                    {{ $slot }}
                </div>
            </div>
        </div>
        <footer class="bg-gray-200 pocition-fixet py-16 text-center text-sm text-black dark:text-white/70">
                <div class="container mx-auto px-6">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="text-center md:text-left mb-4 md:mb-0" >
                            <a href="http://www.unefa.edu.ve/portal/">
                                <h2 class="text-lg font-semibold text-blakc ">UNEFA</h2>
                            </a>
                            <p class="text-sm text-gray-600">© {{ date('Y') }} Todos los derechos reservados.</p>
                            <p class="text-sm text-gray-600">V 3.20 Eve Marín.</p>
                        </div>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-600 hover:text-white transition">Inicio</a>
                            <a href="#" class="text-gray-600 hover:text-white transition">Sobre Nosotros</a>
                            <a href="#" class="text-gray-600 hover:text-white transition">Contacto</a>
                        </div>
                    </div>
                </div>
        </footer>
    </body>
</html>
