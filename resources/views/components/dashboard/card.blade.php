@props(['title', 'value', 'color' => 'blue'])

<div class="bg-white border overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-2 text-{{ $color }}-800">{{ $title }}</h3>
        <p class="text-3xl font-bold text-{{ $color }}-600">{{ $value }}</p>
    </div>
</div>
