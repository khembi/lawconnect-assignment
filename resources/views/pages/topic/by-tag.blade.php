@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Topics tagged with "{{ $tag->name }}"</h1>
        <x-primary-button href="{{ route('topics.create') }}">
            Create New Topic
        </x-primary-button>
    </div>

    <x-topic-grid :topics="$topics" :current-tag="$tag" />

    <div class="mt-6">
        <a href="{{ route('topics.index') }}" class="text-blue-500 hover:text-blue-700">
            &larr; Back to All Topics
        </a>
    </div>
</div>
@endsection 