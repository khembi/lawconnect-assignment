<?php

namespace App\Services;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TopicService
{
    /**
     * Get all topics
     *
     * @return Collection
     */
    public function getAllTopics(): Collection
    {
        return Topic::with('tags')->get();
    }

    /**
     * Get paginated list of topics
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedTopics(int $perPage = 15): LengthAwarePaginator
    {
        return Topic::with('tags')->paginate($perPage);
    }

    /**
     * Get a specific topic by ID
     *
     * @param int $id
     * @return Topic
     */
    public function getTopicById(int $id): Topic
    {
        return Topic::with('tags')->findOrFail($id);
    }

    /**
     * Create a new topic
     *
     * @param array $data
     * @return Topic
     */
    public function createTopic(array $data): Topic
    {
        $topic = Topic::create([
            'name' => $data['name'],
            'content' => $data['content'] ?? null,
            'status' => $data['status'] ?? Topic::STATUS_PUBLISHED,
        ]);

        // Attach tags if provided
        if (isset($data['tag_ids']) && is_array($data['tag_ids'])) {
            $topic->tags()->attach($data['tag_ids']);
        }

        return $topic->load('tags');
    }

    /**
     * Update an existing topic
     *
     * @param Topic $topic
     * @param array $data
     * @return Topic
     */
    public function updateTopic(Topic $topic, array $data): Topic
    {
        $topic->update([
            'name' => $data['name'] ?? $topic->name,
            'content' => $data['content'] ?? $topic->content,
            'status' => $data['status'] ?? $topic->status,
        ]);

        // Sync tags if provided
        if (isset($data['tag_ids']) && is_array($data['tag_ids'])) {
            $topic->tags()->sync($data['tag_ids']);
        }

        return $topic->fresh(['tags']);
    }

    /**
     * Delete a topic
     *
     * @param Topic $topic
     * @return bool
     */
    public function deleteTopic(Topic $topic): bool
    {
        // The tag associations will be automatically removed due to onDelete('cascade')
        return $topic->delete();
    }

    /**
     * Increment view count for a topic
     *
     * @param Topic $topic
     * @return Topic
     */
    public function incrementViews(Topic $topic): Topic
    {
        $topic->increment('views');
        return $topic->fresh();
    }

    /**
     * Get topics by tag
     *
     * @param int $tagId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getTopicsByTag(int $tagId, int $perPage = 15): LengthAwarePaginator
    {
        return Topic::whereHas('tags', function ($query) use ($tagId) {
            $query->where('tags.id', $tagId);
        })
        ->with('tags')
        ->latest()
        ->paginate($perPage);
    }

    /**
     * Attach tags to a topic
     *
     * @param Topic $topic
     * @param array $tagIds
     * @return Topic
     */
    public function attachTags(Topic $topic, array $tagIds): Topic
    {
        $topic->tags()->attach($tagIds);
        return $topic->fresh(['tags']);
    }

    /**
     * Detach tags from a topic
     *
     * @param Topic $topic
     * @param array $tagIds
     * @return Topic
     */
    public function detachTags(Topic $topic, array $tagIds): Topic
    {
        $topic->tags()->detach($tagIds);
        return $topic->fresh(['tags']);
    }

    /**
     * Search topics by title with a given search term
     *
     * @param string $search The search term to filter topics
     * @param int $limit Maximum number of results to return
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\Topic>
     */
    public function searchByTitle(string $search, int $limit = 5): Collection
    {
        return Topic::where('name', 'like', '%' . $search . '%')
            ->take($limit)
            ->get();
    }

    /**
     * Search topics by keyword in title or content
     *
     * @param string $keyword The keyword to search in title and content
     * @param int $limit Maximum number of results to return
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\Topic>
     */
    public function searchByKeyword(string $keyword, int $limit = 5): Collection
    {
        return Topic::where('name', 'like', '%' . $keyword . '%')
            ->orWhere('content', 'like', '%' . $keyword . '%')
            ->take($limit)
            ->get();
    }
} 