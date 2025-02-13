<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="icon" href="{{asset('svg/2sViAPwvYhpvEIpWwI8S6SJgc7S.svg')}}">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('css/min') }}"></script>
        @livewireStyles()
    </head>
    <body class="font-sans antialiased bg-cover bg-center bg-no-repeat bg-[url({{ asset('img/Picsart_25-02-05_01-02-51-216.png')}})] ">
        <div class="min-h-screen ">
            {{-- <img class="absolute z-0  w-full h-full bg-no-repeat" src="{{asset('img/Picsart_25-02-05_01-02-51-216.png')}}"  /> --}}
            <div class=" relative z-10">
                <livewire:layout.navigation />
    
                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-stone-300 dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif
    
                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
        <footer class="bg-gray-200 relative botton-0 z-0 py-16 text-center text-sm text-black dark:text-white/70">
            <div class="container mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-center md:text-left mb-4 md:mb-0">
                        <a href="http://www.unefa.edu.ve/portal/">
                            <h2 class="text-lg font-semibold text-blakc ">UNEFA</h2>
                        </a>
                        <p class="text-sm text-gray-600">© {{ date('Y') }} Todos los derechos reservados.</p>
                    </div>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-600 hover:text-white transition">Inicio</a>
                        <a href="#" class="text-gray-600 hover:text-white transition">Sobre Nosotros</a>
                        <a href="#" class="text-gray-600 hover:text-white transition">Contacto</a>
                    </div>
                </div>
            </div>
        </footer>

        @livewireScripts()
    </body>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log("Livewire cargado correctamente.");

            Livewire.on('alert', function(message) {
                Swal.fire({
                    title: "¡Excelente!",
                    text: message ,
                    icon: "success"
                });
            });

            Livewire.on('loteEliminado', () => {
                // Código para actualizar la tabla, como recargar datos o mostrar un mensaje.
                alert('Lote eliminado exitosamente.');
            });

            Livewire.on('ConfirmarEliminar', function(data) {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: data.id,  // Usando 'message' desde los datos enviados
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, eliminar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $wire.dispatchTo("DespachoQuirofano", "eliminardespacho");
                        Swal.fire({
                            title: "Eliminado!",
                            text: "El despacho ha sido eliminado.",
                            icon: "success"
                        }).then(() => {
                            Swal.fire({
                                title: "¡Excelente!",
                                text: "Finalizado" ,
                                icon: "success"
                            });
                        });
                    }
                });
            });

            Livewire.on('confirmar-eliminacion', data => {
                console.log("Enviando evento eliminar con ID:", data[0].metodo);
                Swal.fire({
                    title: 'Confirmación',
                    text: data[0].menssage,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log("Enviando evento eliminar con ID:", data[0].despachoId);
                        Livewire.dispatch( data[0].metodo , { despachoId: data[0].despachoId });
                        // Livewire.emit('eliminar', data[0].despachoId);
                    }
                });
            });

            Livewire.on('notificacion', data => {
                Swal.fire({
                    title: 'Información',
                    text: data.mensaje,
                    icon: data.tipo
                });
            });

            Livewire.on('abrirPdf', fileUrl => {
                console.log("Enviando para abrir url:", fileUrl[0].fileUrl);
                setTimeout(() => {
                    window.open(fileUrl[0].fileUrl, '_blank'); 
                }, 500); // Espera 500ms antes de abrirlo  
            });
        });

    </script>
</html>
