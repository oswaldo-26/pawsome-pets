@if ($paginator->hasPages())
    <nav class="pagination-nav">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="btn-outline" style="opacity: 0.5; cursor: not-allowed;">← Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn-outline">← Previous</a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn-primary">Next →</a>
        @else
            <span class="btn-primary" style="opacity: 0.5; cursor: not-allowed;">Next →</span>
        @endif
    </nav>
@endif
