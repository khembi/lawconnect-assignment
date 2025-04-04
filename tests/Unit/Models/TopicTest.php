<?php

namespace Tests\Unit\Models;

use App\Models\Tag;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopicTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test topic creation.
     */
    public function test_can_create_topic(): void
    {
        $topic = Topic::factory()->create([
            'name' => 'Test Topic',
            'content' => 'This is a test topic content.',
            'views' => 10,
        ]);

        $this->assertDatabaseHas('topics', [
            'name' => 'Test Topic',
            'content' => 'This is a test topic content.',
            'views' => 10,
        ]);
    }

    /**
     * Test topic-tag relationship.
     */
    public function test_topic_belongs_to_many_tags(): void
    {
        // Create a topic
        $topic = Topic::factory()->create();
        
        // Create tags
        $tags = Tag::factory(3)->create();
        
        // Attach tags to the topic
        $topic->tags()->attach($tags->pluck('id'));
        
        // Assert the relationship works
        $this->assertCount(3, $topic->tags);
        foreach ($tags as $tag) {
            $this->assertTrue($topic->tags->contains($tag));
        }
    }

    /**
     * Test topic factory.
     */
    public function test_topic_factory_works(): void
    {
        // Create a topic using the factory
        $topic = Topic::factory()->create();
        
        // Assert the topic was created with default values
        $this->assertNotNull($topic->name);
        $this->assertNotNull($topic->content);
        $this->assertIsInt($topic->views);
        $this->assertNotNull($topic->created_at);
        $this->assertNotNull($topic->updated_at);
    }

    /**
     * Test topic with tags factory.
     */
    public function test_topic_factory_with_tags(): void
    {
        // Create a topic with 3 tags
        $topic = Topic::factory()
            ->has(Tag::factory()->count(3), 'tags')
            ->create();
        
        // Assert the tags were created and attached
        $this->assertCount(3, $topic->tags);
    }

    /**
     * Test topic status methods.
     */
    public function test_topic_status_methods(): void
    {
        // Create a published topic
        $publishedTopic = Topic::factory()->published()->create();
        
        // Create a draft topic
        $draftTopic = Topic::factory()->draft()->create();
        
        // Test isPublished method
        $this->assertTrue($publishedTopic->isPublished());
        $this->assertFalse($draftTopic->isPublished());
        
        // Test isDraft method
        $this->assertTrue($draftTopic->isDraft());
        $this->assertFalse($publishedTopic->isDraft());
    }

    /**
     * Test getting available statuses.
     */
    public function test_get_statuses(): void
    {
        $statuses = Topic::getStatuses();
        
        $this->assertIsArray($statuses);
        $this->assertArrayHasKey(Topic::STATUS_PUBLISHED, $statuses);
        $this->assertArrayHasKey(Topic::STATUS_DRAFT, $statuses);
        $this->assertEquals('Published', $statuses[Topic::STATUS_PUBLISHED]);
        $this->assertEquals('Draft', $statuses[Topic::STATUS_DRAFT]);
    }
} 