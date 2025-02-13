<x-app-layout>
    <div class="min-h-screen ">
        <img class="absolute z-0  w-full h-full bg-no-repeat" src="{{asset('img/Picsart_25-02-05_01-02-51-216.png')}}"  />
        <div class=" pb-8 relative z-10">
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Profile') }}
                </h2>
            </x-slot>

            <div class="py-12">
                <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="">
                            <livewire:profile.update-profile-information-form />
                        </div>
                    </div>

                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="">
                            <livewire:profile.update-password-form />
                        </div>
                    </div>

                    {{-- Lista de usuarios activos --}}
                    <livewire:profile.tabla-usuarios-activos />

                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <div class="">
                            <livewire:profile.delete-user-form />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
