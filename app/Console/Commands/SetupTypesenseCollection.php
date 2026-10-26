<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Typesense\Client;

#[Signature('typesense:setup')]
#[Description('Command description')]
class SetupTypesenseCollection extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(Client $client)
    {
        $schema = [
            'name' => 'articles',
            'fields' => [
                ['name' => 'id', 'type' => 'string'],
                ['name' => 'title', 'type' => 'string'],
                ['name' => 'body', 'type' => 'string'],
                ['name' => 'category', 'type' => 'string', 'facet' => true],
                ['name' => 'embedding', 'type' => 'float[]', 'num_dim' => 4],
            ]
        ];

        // 1. Drop existing collection if it exists to reset
        try {
            $client->collections['articles']->delete();
            $this->info('Existing collection deleted.');
        } catch (\Exception $e) {
            // Collection might not exist yet, safe to ignore
        }

        // 2. Create Collection
        $client->collections->create($schema);
        $this->info('Typesense collection "articles" created successfully.');

        // 3. Insert Dummy Records
        $documents = [
            [
                'id' => '1',
                'title' => 'Getting Started with Laravel 11',
                'body' => 'Learn how to configure databases, routing, and modern service architecture in Laravel.',
                'category' => 'Backend',
                'embedding' => [0.12, 0.45, 0.89, 0.33]
            ],
            [
                'id' => '2',
                'title' => 'Fast Full-Text Search with Typesense',
                'body' => 'Typesense provides sub-50ms search-as-you-type functionality with built-in typo tolerance.',
                'category' => 'Search',
                'embedding' => [0.91, 0.11, 0.22, 0.74]
            ],
            [
                'id' => '3',
                'title' => 'Building Dynamic UIs with jQuery and Bootstrap',
                'body' => 'Combine asynchronous AJAX calls with Blade templates to produce clean, responsive interfaces.',
                'category' => 'Frontend',
                'embedding' => [0.33, 0.82, 0.15, 0.51]
            ],
        ];

        foreach ($documents as $doc) {
            $client->collections['articles']->documents->create($doc);
        }

        $this->info('Seed documents indexed successfully into Typesense!');
    }
}
