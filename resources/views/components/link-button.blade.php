@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center bg-gray-300 hover:bg-gray-400 text-gray-800 px-3.5 py-2.5 rounded-md text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition']) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => $type, 'class' => 'bg-gray-300 hover:bg-gray-400 text-gray-800 px-3.5 py-2.5 rounded-md text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition']) }}>
        {{ $slot }}
    </button>
@endif 