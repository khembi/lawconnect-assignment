<div class="mt-8">
    <form wire:submit.prevent="searchTopic" class="flex gap-x-4">
        <div class="flex-auto relative">
            <input
                wire:model="search"
                wire:input.debounce.300ms="performSearch"
                type="text"
                name="search"
                class="w-full rounded-md bg-white px-3.5 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                placeholder="Search topics..."
            >
            
            @if(!empty($search) && $searchResults->count() > 0)
                <div class="absolute left-0 right-0 mt-1 bg-white rounded-md shadow-lg border border-gray-200 max-h-60 overflow-y-auto z-50">
                    @foreach($searchResults as $result)
                        <button
                            type="button"
                            wire:click="selectTopic({{ $result->id }})"
                            class="w-full text-left px-4 py-2 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none hover:cursor-pointer"
                        >
                            {{ $result->name }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
        <button 
            type="submit" 
            class="flex-none rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
            Search
        </button>
    </form>
</div>