<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Farmacia</title>

        <!-- Fonts -->
        <script src="{{ asset('css/fontbunny') }}"></script>
        {{-- <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" /> --}}
        <!-- tailwind -->
        <script src="{{ asset('css/min') }}"></script>
        
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans ">
        <div class=" text-black/50 ">
            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                <img class="absolute w-full h-full bg-no-repeat" src="{{asset('img/Picsart_25-02-05_01-02-51-216.png')}}"  />
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl pb-8">
                    <header class="flex flex-col items-center text-center py-10 relative rounded-lg">
                        <!-- Logo -->
                        <div class="h-28 w-28 flex justify-center items-center">
                            <a href="">
                                <img src="{{ asset('svg/2sViAPwvYhpvEIpWwI8S6SJgc7S.svg') }}" alt="Logo Hospimil" class="h-full w-full object-contain">
                            </a>
                        </div>
                        <!-- Título -->
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-200 mt-3">
                            Farmacia Hospitalaria
                        </p>
                    </header>

                    <!-- Login -->
                    <main class="mt-6 flex justify-center min-h-[28rem]">
                        <div class=" bg-white/30 backdrop-blur-md shadow-lg shadow-stone-400/50 p-8 rounded-lg w-[24rem]">
                            <livewire:pages.auth.login />
                        </div>
                    </main>

                </div>
            </div>
        </div>

        <footer class="bg-gray-200 pocition-fixet py-16 text-center text-sm text-black dark:text-white/70">
                <div class="container mx-auto px-6">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="text-center md:text-left mb-4 md:mb-0">
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
