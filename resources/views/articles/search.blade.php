<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel + Typesense Hybrid Search Demo</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4 text-center">Laravel + Typesense Search Demo</h2>
            
            <!-- Search Controls Card -->
            <div class="card shadow-sm p-4 mb-4 bg-white rounded">
                <div class="row g-3">
                    <div class="col-md-7">
                        <label for="searchBox" class="form-label fw-bold">Keyword / Phrase</label>
                        <input type="text" id="searchBox" class="form-control" placeholder="Type keywords (e.g., Laravel, Search)...">
                    </div>
                    <div class="col-md-3">
                        <label for="categoryFilter" class="form-label fw-bold">Category Facet</label>
                        <select id="categoryFilter" class="form-select">
                            <option value="">All Categories</option>
                            <option value="Backend">Backend</option>
                            <option value="Search">Search</option>
                            <option value="Frontend">Frontend</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="semanticToggle">
                            <label class="form-check-label small fw-bold" for="semanticToggle">Hybrid</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            <div id="resultsList" class="list-group">
                <div class="text-center text-muted py-4">Start typing above to query Typesense instantly...</div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    function performSearch() {
        let query = $('#searchBox').val().trim() || '*';
        let category = $('#categoryFilter').val();
        let semantic = $('#semanticToggle').is(':checked') ? 1 : 0;

        $.ajax({
            url: "{{ route('articles.api.search') }}",
            method: 'GET',
            data: { 
                q: query, 
                category: category,
                semantic: semantic
            },
            success: function(response) {
                let $list = $('#resultsList');
                $list.empty();

                if (response.hits && response.hits.length > 0) {
                    response.hits.forEach(function(hit) {
                        let doc = hit.document;
                        let textMatchScore = hit.text_match || 'N/A';
                        
                        let html = `
                            <div class="list-group-item list-group-item-action mb-2 shadow-sm rounded p-3">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <h5 class="mb-1 text-primary">${doc.title}</h5>
                                    <div>
                                        <span class="badge bg-secondary">${doc.category}</span>
                                        <span class="badge bg-info text-dark">Score: ${textMatchScore}</span>
                                    </div>
                                </div>
                                <p class="mb-1 text-muted mt-2">${doc.body}</p>
                            </div>
                        `;
                        $list.append(html);
                    });
                } else {
                    $list.html('<div class="alert alert-warning text-center">No matching articles found.</div>');
                }
            },
            error: function(err) {
                console.error("Typesense search error:", err);
            }
        });
    }

    // Debounced live typing execution
    let debounceTimer;
    $('#searchBox').on('keyup', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(performSearch, 250);
    });

    $('#categoryFilter, #semanticToggle').on('change', function() {
        performSearch();
    });
});
</script>

</body>
</html>