<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Tag;
use App\Models\Topic;
use App\Services\TopicService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TopicControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test the index page displays correctly.
     */
    public function test_index_displays_topics(): void
    {
        // Create some topics
        $topics = Topic::factory(3)->create();

        // Visit the index page
        $response = $this->get(route('topics.index'));

        // Assert the response is successful
        $response->assertStatus(200);
        
        // Assert the view has the topics
        $response->assertViewHas('topics');
        
        // Assert each topic is visible
        foreach ($topics as $topic) {
            $response->assertSee($topic->name);
        }
    }

    /**
     * Test the search functionality works.
     */
    public function test_index_can_search_topics(): void
    {
        // Create topics with specific names
        $matchingTopic = Topic::factory()->create(['name' => 'Laravel']);
        $nonMatchingTopic = Topic::factory()->create(['name' => 'Vue.js']);

        // Search for 'Laravel'
        $response = $this->get(route('topics.index', ['q' => 'Laravel']));

        // Assert the response is successful
        $response->assertStatus(200);
        
        // Assert the matching topic is visible
        $response->assertSee($matchingTopic->name);
        
        // Assert the non-matching topic is not visible
        $response->assertDontSee($nonMatchingTopic->name);
    }

    /**
     * Test the create page displays correctly.
     */
    public function test_create_displays_form(): void
    {
        // Create some tags for the form
        $tags = Tag::factory(3)->create();

        // Visit the create page
        $response = $this->get(route('topics.create'));

        // Assert the response is successful
        $response->assertStatus(200);
        
        // Assert the form elements are present
        $response->assertSee('Create New Topic');
        $response->assertSee('Topic Name');
        $response->assertSee('Content');
        $response->assertSee('Tags');
        
        // Assert all tags are visible in the form
        foreach ($tags as $tag) {
            $response->assertSee($tag->name);
        }
    }

    /**
     * Test storing a new topic.
     */
    public function test_store_creates_new_topic(): void
    {
        // Create tags to attach
        $tags = Tag::factory(2)->create();
        
        // Prepare topic data
        $topicData = [
            'name' => 'New Test Topic',
            'content' => 'This is the content of the test topic.',
            'tag_ids' => $tags->pluck('id')->toArray(),
        ];

        // Submit the form
        $response = $this->post(route('topics.store'), $topicData);

        // Assert the topic was created in the database
        $this->assertDatabaseHas('topics', [
            'name' => 'New Test Topic',
            'content' => 'This is the content of the test topic.',
        ]);
        
        // Get the created topic
        $topic = Topic::where('name', 'New Test Topic')->first();
        
        // Assert tags were attached
        foreach ($tags as $tag) {
            $this->assertDatabaseHas('tag_topic', [
                'topic_id' => $topic->id,
                'tag_id' => $tag->id,
            ]);
        }
        
        // Assert redirect to the show page
        $response->assertRedirect(route('topics.show', $topic));
        $response->assertSessionHas('success');
    }

    /**
     * Test validation errors when storing a topic.
     */
    public function test_store_validates_input(): void
    {
        // Submit invalid data (missing required fields)
        $response = $this->post(route('topics.store'), [
            'name' => '',
            'content' => '',
        ]);

        // Assert validation errors
        $response->assertSessionHasErrors(['name', 'content']);
        
        // Assert no topic was created
        $this->assertEquals(0, Topic::count());
    }

    /**
     * Test showing a topic.
     */
    public function test_show_displays_topic(): void
    {
        // Create a topic with tags
        $topic = Topic::factory()->create([
            'name' => 'Test Topic',
            'content' => 'This is the content of the test topic.',
            'views' => 5,
        ]);
        
        $tags = Tag::factory(2)->create();
        $topic->tags()->attach($tags->pluck('id'));

        // Visit the show page
        $response = $this->get(route('topics.show', $topic));

        // Assert the response is successful
        $response->assertStatus(200);
        
        // Assert the topic details are visible
        $response->assertSee($topic->name);
        $response->assertSee($topic->content);
        
        // Assert the tags are visible
        foreach ($tags as $tag) {
            $response->assertSee($tag->name);
        }
        
        // Assert the view count increased
        $this->assertEquals(6, $topic->fresh()->views);
    }

    /**
     * Test the edit page displays correctly.
     */
    public function test_edit_displays_form_with_topic_data(): void
    {
        // Create a topic with tags
        $topic = Topic::factory()->create([
            'name' => 'Test Topic',
            'content' => 'This is the content of the test topic.',
        ]);
        
        $tags = Tag::factory(3)->create();
        $selectedTags = $tags->take(2);
        $topic->tags()->attach($selectedTags->pluck('id'));

        // Visit the edit page
        $response = $this->get(route('topics.edit', $topic));

        // Assert the response is successful
        $response->assertStatus(200);
        
        // Assert the form is pre-filled with topic data
        $response->assertSee('Edit Topic');
        $response->assertSee($topic->name);
        $response->assertSee($topic->content);
        
        // Assert all tags are visible
        foreach ($tags as $tag) {
            $response->assertSee($tag->name);
        }
    }

    /**
     * Test updating a topic.
     */
    public function test_update_modifies_topic(): void
    {
        // Create a topic with tags
        $topic = Topic::factory()->create();
        $oldTags = Tag::factory(2)->create();
        $topic->tags()->attach($oldTags->pluck('id'));
        
        // Create new tags for updating
        $newTags = Tag::factory(2)->create();
        
        // Prepare updated data
        $updatedData = [
            'name' => 'Updated Topic Name',
            'content' => 'This is the updated content.',
            'tag_ids' => $newTags->pluck('id')->toArray(),
        ];

        // Submit the update
        $response = $this->put(route('topics.update', $topic), $updatedData);

        // Assert the topic was updated in the database
        $this->assertDatabaseHas('topics', [
            'id' => $topic->id,
            'name' => 'Updated Topic Name',
            'content' => 'This is the updated content.',
        ]);
        
        // Assert old tags were detached and new tags attached
        foreach ($oldTags as $tag) {
            $this->assertDatabaseMissing('tag_topic', [
                'topic_id' => $topic->id,
                'tag_id' => $tag->id,
            ]);
        }
        
        foreach ($newTags as $tag) {
            $this->assertDatabaseHas('tag_topic', [
                'topic_id' => $topic->id,
                'tag_id' => $tag->id,
            ]);
        }
        
        // Assert redirect to the show page
        $response->assertRedirect(route('topics.show', $topic));
        $response->assertSessionHas('success');
    }

    /**
     * Test deleting a topic.
     */
    public function test_destroy_removes_topic(): void
    {
        // Create a topic with tags
        $topic = Topic::factory()->create();
        $tags = Tag::factory(2)->create();
        $topic->tags()->attach($tags->pluck('id'));

        // Delete the topic
        $response = $this->delete(route('topics.destroy', $topic));

        // Assert the topic was removed from the database
        $this->assertDatabaseMissing('topics', ['id' => $topic->id]);
        
        // Assert the tag associations were removed
        foreach ($tags as $tag) {
            $this->assertDatabaseMissing('tag_topic', [
                'topic_id' => $topic->id,
                'tag_id' => $tag->id,
            ]);
        }
        
        // Assert redirect to the index page
        $response->assertRedirect(route('topics.index'));
        $response->assertSessionHas('success');
    }

    /**
     * Test viewing topics by tag.
     */
    public function test_topics_by_tag_displays_filtered_topics(): void
    {
        // Create topics
        $topics = Topic::factory(3)->create();
        
        // Create a tag
        $tag = Tag::factory()->create();
        
        // Attach the tag to only the first topic
        $topics[0]->tags()->attach($tag->id);

        // Visit the topics-by-tag page
        $response = $this->get(route('topics.index', ['tag' => $tag->id]));

        // Assert the response is successful
        $response->assertStatus(200);
        
        // Assert the matching topic is visible
        $response->assertSee($topics[0]->name);
        
        // Assert the non-matching topics are not visible
        $response->assertDontSee($topics[1]->name);
        $response->assertDontSee($topics[2]->name);
    }

    /**
     * Test updating a topic's status.
     */
    public function test_can_update_topic_status(): void
    {
        // Create a topic with draft status
        $topic = Topic::factory()->draft()->create();
        
        // Prepare updated data
        $updatedData = [
            'name' => 'Updated Topic Name',
            'content' => 'This is the updated content.',
        ];

        // Submit the update
        $response = $this->put(route('topics.update', $topic), $updatedData);
        
        // Assert the topic status was updated
        $this->assertEquals(Topic::STATUS_PUBLISHED, $topic->fresh()->status);
        
        // Assert redirect back
        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
} 