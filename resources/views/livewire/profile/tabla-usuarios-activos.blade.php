<div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
    @if ($tipo === 'Admin')
    <div class="">
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                {{ __('Lista de Usuarios Registrados') }}
            </h2>

            @if (session()->has('message'))
                <div class="mb-4 text-sm text-green-600 dark:text-green-400">
                    {{ session('message') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300 dark:border-gray-700">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">ID</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Nombre</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Tipo</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-200 dark:hover:bg-gray-600">
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-center">{{ $user->id }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $user->name }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-center">{{ ucfirst($user->tipo) }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-center">
                                    <button wire:click="deleteUser({{ $user->id }})"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="">
        <p class="block font-medium text-gray-800 dark:text-gray-800 mt-2 text-[1.1rem] "> No tiene permisos para ver la tabla de usuarios</p>
    </div>
    @endif
</div>