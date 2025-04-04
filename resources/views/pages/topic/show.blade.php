@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white overflow-hidden">
        <div class="p-6">
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach($topic->tags as $tag)
                <x-tag :tag="$tag" />
                @endforeach
            </div>
            <div class="flex justify-between items-start mb-6">
                <h1 class="mt-2 text-pretty text-3xl font-semibold tracking-tight text-gray-900">{{ $topic->name }}</h1>
                <div class="flex space-x-2">
                    <x-primary-button href="{{ route('topics.edit', $topic) }}">
                        Edit
                    </x-primary-button>
                    <div x-data="{ isOpen: false }">
                        <form action="{{ route('topics.destroy', $topic) }}" method="POST" id="deleteTopicForm">
                            @csrf
                            @method('DELETE')
                            <x-danger-button type="button" @click="isOpen = true">
                                Delete
                            </x-danger-button>
                        </form>

                        <div x-show="isOpen" 
                             x-cloak
                             class="fixed inset-0 bg-gray-500/50"
                             @click="isOpen = false">
                        </div>

                        <div x-show="isOpen"
                             x-cloak
                             class="fixed inset-0 flex items-center justify-center"
                             @click.self="isOpen = false">
                            <div x-show="isOpen"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-90"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-90"
                                 class="bg-white rounded-lg shadow-lg p-4 max-w-md w-full mx-4">
                                <h2 class="text-lg font-medium">Delete Topic</h2>
                                <p class="mt-2">Are you sure you want to delete this topic?</p>
                                
                                <div class="flex justify-end gap-2 mt-4">
                                    <button type="button" 
                                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                            @click="isOpen = false">
                                        Cancel
                                    </button>
                                    <button type="button"
                                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700"
                                            @click="document.getElementById('deleteTopicForm').submit()">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="prose max-w-none mb-6">
                {!! nl2br(e($topic->content)) !!}
            </div>

            <div class="flex justify-between items-center text-sm text-gray-500 pt-4">
                <span>{{ $topic->views }} views</span>
                <span>Created {{ $topic->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>
</div>
@endsection 