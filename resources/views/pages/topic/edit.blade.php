@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto bg-white overflow-hidden">
        <div class="">
            <h1 class="text-2xl font-bold mb-6">{{ $topic->isPublished() ? 'Edit' : 'Publish' }} Topic</h1>

            <form action="{{ route('topics.update', $topic) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <x-form-input 
                    type="text"
                    name="name" 
                    label="Topic Name" 
                    placeholder="Enter topic name" 
                    :required="true"
                    :value="$topic->name"
                />

                <x-form-textarea 
                    name="content" 
                    label="Content" 
                    placeholder="Enter topic content" 
                    :required="true"
                    :value="$topic->content"
                    :rows="8"
                />

                <x-form-select 
                    name="tag_ids" 
                    label="Tags" 
                    :multiple="true"
                    :options="$tags->pluck('name', 'id')->toArray()"
                    :selected="old('tag_ids', $topic->tags->pluck('id')->toArray())"
                />

                <div class="flex justify-between pt-4">
                    <x-link-button href="{{ route('topics.show', $topic) }}">
                        Cancel
                    </x-link-button>
                    <x-primary-button type="submit">
                        {{ $topic->isPublished() ? 'Submit' : 'Publish' }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 