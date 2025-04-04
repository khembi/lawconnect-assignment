<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Topic;
use App\Services\TopicService;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    protected $topicService;

    public function __construct(TopicService $topicService)
    {
        $this->topicService = $topicService;
    }

    /**
     * Display a listing of the topics.
     */
    public function index(Request $request)
    {
        if ($request->has('q')) {
            $topics = $this->topicService->searchByKeyword($request->input('q'));
            $query = $request->input('q');
        } elseif ($request->has('tag')) {
            $topics = $this->topicService->getTopicsByTag($request->input('tag'));
            $query = null;
        } else {
            $topics = $this->topicService->getPaginatedTopics();
            $query = null;
        }

        return view('pages.topic.index', compact('topics', 'query'));
    }

    /**
     * Show the form for creating a new topic.
     */
    public function create()
    {
        $tags = Tag::all();
        return view('pages.topic.create', compact('tags'));
    }

    /**
     * Store a newly created topic in storage.
     */
    public function store(Request $request)
    {
        // Basic validation
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:topics',
            'content' => 'required|string',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $topic = $this->topicService->createTopic($validated);

        return redirect()->route('topics.show', $topic)
            ->with('success', 'Topic created successfully.');
    }

    /**
     * Display the specified topic.
     */
    public function show(Topic $topic)
    {
        // Increment view count
        $topic = $this->topicService->incrementViews($topic);
        return view('pages.topic.show', compact('topic'));
    }

    /**
     * Show the form for editing the specified topic.
     */
    public function edit(Topic $topic)
    {
        $tags = Tag::all();
        return view('pages.topic.edit', compact('topic', 'tags'));
    }

    /**
     * Update the specified topic in storage.
     */
    public function update(Request $request, Topic $topic)
    {
        // Basic validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        if (!$topic->isPublished()) {
            $validated['status'] = Topic::STATUS_PUBLISHED;
        }

        $topic = $this->topicService->updateTopic($topic, $validated);

        return redirect()->route('topics.show', $topic)
            ->with('success', 'Topic updated successfully.');
    }

    /**
     * Remove the specified topic from storage.
     */
    public function destroy(Topic $topic)
    {
        $this->topicService->deleteTopic($topic);

        return redirect()->route('topics.index')
            ->with('success', 'Topic deleted successfully.');
    }
}
