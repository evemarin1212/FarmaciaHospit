<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @livewireStyles()
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            </footer>
        </div>

        @livewireScripts()

        <script>
            Livewire.on('alert', function(message) {
                Swal.fire({
                    title: "¡Excelente!",
                    text: message ,
                    icon: "success"
                });
            })
            Livewire.on('loteEliminado', () => {
                // Código para actualizar la tabla, como recargar datos o mostrar un mensaje.
                alert('Lote eliminado exitosamente.');
            });
            document.addEventListener('livewire:load', function () {
                try {
                    // Ahora que Livewire ha cargado, puedes definir tu evento y manejarlo
                    Livewire.on('ConfirmarEliminar', function(message, id) {
                        Swal.fire({
                            title: "¿Estás seguro?",
                            text: message,
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Sí, eliminar!"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Livewire.dispatch('eliminardespacho', id); // Llamamos al método de Livewire
                                Swal.fire({
                                    title: "Eliminado!",
                                    text: "El despacho ha sido eliminado.",
                                    icon: "success"
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        });
                    });
                } catch (error) {
                    console.error("Error al manejar el evento Livewire:", error);
                }
            });
        </script>
    </body>
</html>
