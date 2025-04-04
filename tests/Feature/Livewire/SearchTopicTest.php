<?php

namespace Tests\Feature\Livewire;

use App\Livewire\SearchTopic;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SearchTopicTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the component renders correctly.
     */
    public function test_component_renders(): void
    {
        Livewire::test(SearchTopic::class)
            ->assertSee('Search topics');
    }

    /**
     * Test redirecting to a topic.
     */
    public function test_can_redirect_to_topic(): void
    {
        $topic = Topic::factory()->create();

        Livewire::test(SearchTopic::class)
            ->call('selectTopic', $topic->id)
            ->assertRedirect(route('topics.show', $topic));
    }
} 