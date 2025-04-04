<?php

namespace App\Livewire;

use App\Services\TopicService;
use Livewire\Component;
use App\Services\AITopicGeneratorService;

class SuggestTopic extends Component
{
    public string $query = '';
    protected AITopicGeneratorService $aiTopicGenerator;

    public function boot(AITopicGeneratorService $aiTopicGenerator)
    {
        $this->aiTopicGenerator = $aiTopicGenerator;
    }

    public function suggestTopic()
    {
        $topic = $this->aiTopicGenerator->generateTopic($this->query);
        return redirect()->route('topics.edit', $topic);
    }

    public function render()
    {
        return view('livewire.suggest-topic');
    }
}
