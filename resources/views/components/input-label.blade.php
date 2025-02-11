@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-gray-800 dark:text-gray-800 mt-2 text-[1.1rem] ']) }}>
    <span class="after:content-['*'] after:text-red-500 after:ml-1"></span>
    {{ $value ?? $slot }}
</label>
