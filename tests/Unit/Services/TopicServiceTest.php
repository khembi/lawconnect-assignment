<?php

namespace Tests\Unit\Services;

use App\Models\Tag;
use App\Models\Topic;
use App\Services\TopicService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopicServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TopicService $topicService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->topicService = new TopicService();
    }

    /**
     * Test getting all topics.
     */
    public function test_get_all_topics(): void
    {
        // Create topics
        $topics = Topic::factory(3)->create();
        
        // Get all topics using the service
        $result = $this->topicService->getAllTopics();
        
        // Assert the correct number of topics is returned
        $this->assertCount(3, $result);
        
        // Assert the topics are the same
        foreach ($topics as $topic) {
            $this->assertTrue($result->contains($topic));
        }
    }

    /**
     * Test getting paginated topics.
     */
    public function test_get_paginated_topics(): void
    {
        // Create topics
        Topic::factory(15)->create();
        
        // Get paginated topics (default 15 per page)
        $result = $this->topicService->getPaginatedTopics();
        
        // Assert pagination works
        $this->assertEquals(15, $result->count());
        $this->assertEquals(15, $result->total());
        
        // Test with custom per page
        Topic::factory(5)->create();
        $result = $this->topicService->getPaginatedTopics(10);
        
        // Assert custom pagination works
        $this->assertEquals(10, $result->count());
        $this->assertEquals(20, $result->total());
    }

    /**
     * Test getting a topic by ID.
     */
    public function test_get_topic_by_id(): void
    {
        // Create a topic
        $topic = Topic::factory()->create();
        
        // Get the topic by ID
        $result = $this->topicService->getTopicById($topic->id);
        
        // Assert the correct topic is returned
        $this->assertEquals($topic->id, $result->id);
        $this->assertEquals($topic->name, $result->name);
        $this->assertEquals($topic->content, $result->content);
    }

    /**
     * Test creating a topic.
     */
    public function test_create_topic(): void
    {
        // Create tags
        $tags = Tag::factory(2)->create();
        
        // Prepare topic data
        $data = [
            'name' => 'New Topic',
            'content' => 'Topic content',
            'tag_ids' => $tags->pluck('id')->toArray(),
        ];
        
        // Create the topic
        $topic = $this->topicService->createTopic($data);
        
        // Assert the topic was created with correct data
        $this->assertEquals('New Topic', $topic->name);
        $this->assertEquals('Topic content', $topic->content);
        $this->assertEquals(0, $topic->views);
        
        // Assert tags were attached
        $this->assertCount(2, $topic->tags);
        foreach ($tags as $tag) {
            $this->assertTrue($topic->tags->contains($tag));
        }
    }

    /**
     * Test updating a topic.
     */
    public function test_update_topic(): void
    {
        // Create a topic with tags
        $topic = Topic::factory()->create();
        $oldTags = Tag::factory(2)->create();
        $topic->tags()->attach($oldTags->pluck('id'));
        
        // Create new tags
        $newTags = Tag::factory(2)->create();
        
        // Prepare update data
        $data = [
            'name' => 'Updated Name',
            'content' => 'Updated content',
            'tag_ids' => $newTags->pluck('id')->toArray(),
        ];
        
        // Update the topic
        $updatedTopic = $this->topicService->updateTopic($topic, $data);
        
        // Assert the topic was updated
        $this->assertEquals('Updated Name', $updatedTopic->name);
        $this->assertEquals('Updated content', $updatedTopic->content);
        
        // Assert tags were synced
        $this->assertCount(2, $updatedTopic->tags);
        foreach ($newTags as $tag) {
            $this->assertTrue($updatedTopic->tags->contains($tag));
        }
        foreach ($oldTags as $tag) {
            $this->assertFalse($updatedTopic->tags->contains($tag));
        }
    }

    /**
     * Test deleting a topic.
     */
    public function test_delete_topic(): void
    {
        // Create a topic
        $topic = Topic::factory()->create();
        
        // Delete the topic
        $result = $this->topicService->deleteTopic($topic);
        
        // Assert the deletion was successful
        $this->assertTrue($result);
        $this->assertDatabaseMissing('topics', ['id' => $topic->id]);
    }

    /**
     * Test incrementing views.
     */
    public function test_increment_views(): void
    {
        // Create a topic with initial views
        $topic = Topic::factory()->create(['views' => 5]);
        
        // Increment views
        $updatedTopic = $this->topicService->incrementViews($topic);
        
        // Assert views were incremented
        $this->assertEquals(6, $updatedTopic->views);
    }

    /**
     * Test getting topics by tag.
     */
    public function test_get_topics_by_tag(): void
    {
        // Create topics
        $topics = Topic::factory(3)->create();
        
        // Create a tag
        $tag = Tag::factory()->create();
        
        // Attach the tag to only the first topic
        $topics[0]->tags()->attach($tag->id);
        
        // Get topics by tag
        $result = $this->topicService->getTopicsByTag($tag->id);
        
        // Assert only the matching topic is returned
        $this->assertEquals(1, $result->count());
        $this->assertEquals($topics[0]->id, $result->first()->id);
    }

    /**
     * Test attaching tags to a topic.
     */
    public function test_attach_tags(): void
    {
        // Create a topic
        $topic = Topic::factory()->create();
        
        // Create tags
        $tags = Tag::factory(2)->create();
        
        // Attach tags
        $updatedTopic = $this->topicService->attachTags($topic, $tags->pluck('id')->toArray());
        
        // Assert tags were attached
        $this->assertCount(2, $updatedTopic->tags);
        foreach ($tags as $tag) {
            $this->assertTrue($updatedTopic->tags->contains($tag));
        }
    }

    /**
     * Test detaching tags from a topic.
     */
    public function test_detach_tags(): void
    {
        // Create a topic
        $topic = Topic::factory()->create();
        
        // Create and attach tags
        $tags = Tag::factory(3)->create();
        $topic->tags()->attach($tags->pluck('id'));
        
        // Detach only the first tag
        $updatedTopic = $this->topicService->detachTags($topic, [$tags[0]->id]);
        
        // Assert the first tag was detached
        $this->assertCount(2, $updatedTopic->tags);
        $this->assertFalse($updatedTopic->tags->contains($tags[0]));
        $this->assertTrue($updatedTopic->tags->contains($tags[1]));
        $this->assertTrue($updatedTopic->tags->contains($tags[2]));
    }

    /**
     * Test searching topics by title.
     */
    public function test_search_by_title(): void
    {
        // Create topics with specific names
        $matchingTopic1 = Topic::factory()->create(['name' => 'Laravel Tips']);
        $matchingTopic2 = Topic::factory()->create(['name' => 'Advanced Laravel']);
        $nonMatchingTopic = Topic::factory()->create(['name' => 'Vue.js Guide']);
        
        // Search for 'Laravel'
        $results = $this->topicService->searchByTitle('Laravel');
        
        // Assert only matching topics are returned
        $this->assertCount(2, $results);
        $this->assertTrue($results->contains($matchingTopic1));
        $this->assertTrue($results->contains($matchingTopic2));
        $this->assertFalse($results->contains($nonMatchingTopic));
        
        // Test with limit
        $limitedResults = $this->topicService->searchByTitle('Laravel', 1);
        $this->assertCount(1, $limitedResults);
    }
} 