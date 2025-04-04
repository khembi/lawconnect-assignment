<div>
    <label for="{{ $name }}" class="block text-sm/6 font-medium text-gray-900">{{ $label }}</label>
    <div class="mt-2">
        <textarea 
            name="{{ $name }}" 
            id="{{ $name }}" 
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6']) }}
        >{{ $value ?? old($name) }}</textarea>
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div> 