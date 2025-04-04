@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div>
        @if($topics->isEmpty())
        <div class="text-center">
            <svg class="mx-auto size-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">No Topics Found</h3>
            @if ($query)
                <p class="mt-1 text-sm text-gray-500">AI will suggest an answer for you.</p>
                <div class="mt-6">
                @livewire('suggest-topic', ['query' => $query])
                </div>
            @endif
        </div>

        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach($topics as $topic)
            <article class="bg-white overflow-hidden">
                <div class="group relative">
                    <h3 class="mt-3 text-lg/6 font-semibold text-indigo-600 group-hover:text-indigo-800">
                        <a href="{{ route('topics.show', $topic) }}">
                            {{ $topic->name }}
                        </a>
                    </h3>
                    
                    <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600">
                        {{ Str::limit($topic->content, 150) }}
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach($topic->tags as $tag)
                        <x-tag :tag="$tag" />
                        @endforeach
                    </div>
                    
                    <div class="mt-3 flex justify-between items-center text-sm text-gray-500">
                        <span>{{ $topic->views }} views</span>
                        <time datetime="{{ $topic->created_at->format('Y-m-d') }}">{{ $topic->created_at->diffForHumans() }}</time>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        @if($topics instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-6">
            {{ $topics->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection 