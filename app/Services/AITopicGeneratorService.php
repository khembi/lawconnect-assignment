<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\Topic;
use Prism\Prism\Prism;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Schema\EnumSchema;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;

class AITopicGeneratorService
{
    public function __construct(
        protected TopicService $topicService
    ) {}

    /**
     * Generate a topic using AI based on a query
     *
     * @param string $query The user's question
     * @return Topic The generated topic
     */
    public function generateTopic(string $query): Topic
    {
        $schema = $this->createTopicSchema();
        $response = $this->getAIResponse($query, $schema);
        return $this->createTopicFromResponse($response);
    }

    /**
     * Create the schema for AI topic generation
     */
    private function createTopicSchema(): ObjectSchema
    {
        $tags = Tag::all()->pluck('name')->toArray();

        return new ObjectSchema(
            name: 'monopoly_knowledge_base',
            description: 'Act as a monopoly expert, Answer the question only in 3 sentences',
            properties: [
                new StringSchema('name', 'The topic title'),
                new StringSchema('content', 'The topic content'),
                new EnumSchema(
                    name: 'tag',
                    description: 'The current appropriate tag of the topic',
                    options: $tags
                )
            ],
            requiredFields: ['name', 'content', 'tag']
        );
    }

    /**
     * Get structured response from AI
     */
    private function getAIResponse(string $query, ObjectSchema $schema): object
    {
        return Prism::structured()
            ->using(Provider::OpenAI, 'gpt-4o')
            ->withSchema($schema)
            ->withPrompt('Act as a monopoly expert, Answer the question: ' . $query)
            ->asStructured();
    }

    /**
     * Create a topic from AI response
     */
    private function createTopicFromResponse(object $response): Topic
    {
        $structured_response = $response->structured;
        $structured_response['tag_ids'] = Tag::where('name', $structured_response['tag'])->pluck('id')->toArray();
        $structured_response['status'] = Topic::STATUS_DRAFT;
        
        return $this->topicService->createTopic($structured_response);
    }
} 