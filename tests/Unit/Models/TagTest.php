<?php

namespace Tests\Unit\Models;

use App\Models\Tag;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test tag creation.
     */
    public function test_can_create_tag(): void
    {
        $tag = Tag::factory()->create([
            'name' => 'Laravel',
        ]);

        $this->assertDatabaseHas('tags', [
            'name' => 'Laravel',
        ]);
    }

    /**
     * Test tag-topic relationship.
     */
    public function test_tag_has_many_topics(): void
    {
        // Create a tag
        $tag = Tag::factory()->create();
        
        // Create topics
        $topics = Topic::factory(3)->create();
        
        // Attach topics to the tag
        $tag->topics()->attach($topics->pluck('id'));
        
        // Assert the relationship works
        $this->assertCount(3, $tag->topics);
        foreach ($topics as $topic) {
            $this->assertTrue($tag->topics->contains($topic));
        }
    }
} 