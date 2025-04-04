@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto bg-white overflow-hidden">
        <div>
            
            <div class="flex justify-between items-start space-x-2">
                <h1 class="text-2xl font-bold mb-6">Create New Topic</h1>
                @livewire('suggest-topic')
            </div>

            <form action="{{ route('topics.store') }}" method="POST" class="space-y-6">
                @csrf

                <x-form-input 
                    type="text"
                    name="name" 
                    label="Topic Name" 
                    placeholder="Enter topic name" 
                    :required="true"
                />

                <x-form-textarea 
                    name="content" 
                    label="Content" 
                    placeholder="Enter topic content" 
                    :required="true"
                    :rows="8"
                />

                <x-form-select 
                    name="tag_ids" 
                    label="Tags" 
                    :multiple="true"
                    :options="$tags->pluck('name', 'id')->toArray()"
                    :selected="old('tag_ids', [])"
                />

                <div class="flex justify-between pt-4">
                    <x-link-button href="{{ route('topics.index') }}">
                        Cancel
                    </x-link-button>
                    <x-primary-button type="submit">
                        Create Topic
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 