<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Topic;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Prism\Prism\Prism;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tags = [
            'rules' => 'Game Rules',
            'tips' => 'Strategies & Tips',
            'faq' => 'Troubleshooting',
        ];

        foreach ($tags as $name) {
            Tag::updateOrCreate(
                ['name' => $name]
            );
        }
    }
}