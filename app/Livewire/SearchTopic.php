<?php

namespace App\Livewire;

use App\Services\TopicService;
use Livewire\Component;

class SearchTopic extends Component
{
    public $search = '';
    public $searchResults;

    protected TopicService $topicService;

    public function boot(TopicService $topicService)
    {
        $this->topicService = $topicService;
    }

    public function mount()
    {
        $this->searchResults = collect();
    }

    public function performSearch()
    {
        if (empty($this->search)) {
            $this->searchResults = collect();
            return;
        }

        $this->searchResults = $this->topicService->searchByKeyword($this->search);
    }

    public function selectTopic($topicId)
    {
        return redirect()->route('topics.show', $topicId);
    }

    public function searchTopic()
    {
        if (empty($this->search)) {
            return redirect()->route('topics.index');
        }

        return redirect()->route('topics.index', [
            'q' => $this->search
        ]);
    }

    public function render()
    {
        return view('livewire.search-topic');
    }
}
