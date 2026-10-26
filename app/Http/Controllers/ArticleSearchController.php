<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Typesense\Client;

class ArticleSearchController extends Controller
{
    protected Client $typesense;

    public function __construct(Client $client)
    {
        $this->typesense = $client;
    }

    // Render Blade View
    public function index()
    {
        return view('articles.search');
    }

    // Handle AJAX Search Endpoint (Keyword + Vector Hybrid Capabilities)
    public function search(Request $request)
    {
        $query = $request->input('q', '*');
        $category = $request->input('category');
        $useSemantic = $request->input('semantic', false);

        $searchParameters = [
            'q' => $query,
            'query_by' => 'title,body',
            'per_page' => 10,
        ];

        // Apply Facet Filtering if a category is picked
        if (!empty($category)) {
            $searchParameters['filter_by'] = "category:=[$category]";
        }

        // Hybrid / Vector Search Query Injection Demo
        // If semantic mode is active and user provided a search term, we can query via vector score fusion
        if ($useSemantic && !empty($query) && $query !== '*') {
            // For this demo, we simulate a vector query lookup using dummy 4-dimensional weights
            // In production, you'd pass your text through an embedding API (e.g. OpenAI / HuggingFace) to get float[] array
            $mockQueryVector = [0.15, 0.40, 0.85, 0.30];
            
            $searchParameters['vector_query'] = 'embedding:(' . json_encode($mockQueryVector) . ', k: 3, alpha: 0.5)';
            // Note: alpha balances keyword search (0) vs vector search (1). 0.5 gives a true Hybrid search blend.
        }

        try {
            $results = $this->typesense->collections['articles']->documents->search($searchParameters);
            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}