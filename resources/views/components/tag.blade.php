<a href="{{ route('topics.index', ['tag' => $tag->id]) }}" 
   {{ $attributes->merge(['class' => 'inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10']) }}>
    {{ $tag->name }}
</a> 