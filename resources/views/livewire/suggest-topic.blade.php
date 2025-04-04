<div x-data="{ isOpen: false }">
    <x-primary-button type="button" @click="isOpen = true">
        Generate Topic
    </x-primary-button>

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
            <h2 class="text-lg font-medium">Generate Topic</h2>

            <x-form-input
                wire:model="query"
                type="text"
                name="query"
                label="Please enter a question and we will generate a topic for you."
                placeholder="Enter question"
                :required="true" />

            <div class="flex justify-end gap-2 mt-4">
                <button type="button"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 hover:cursor-pointer"
                    @click="isOpen = false">
                    Cancel
                </button>
                <x-primary-button wire:click="suggestTopic" type="button">
                    <div wire:loading>
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    Suggest Answer
                </x-primary-button>
            </div>
        </div>
    </div>
</div>