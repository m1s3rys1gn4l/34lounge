@if ($paginator->hasPages())
<nav class="pagination-bar" role="navigation" aria-label="Pagination">
  <div class="pagination-summary">
    Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
  </div>

  <div class="pagination-links">
    @if ($paginator->onFirstPage())
      <span class="page-link disabled">‹ Prev</span>
    @else
      <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹ Prev</a>
    @endif

    @foreach ($elements as $element)
      @if (is_string($element))
        <span class="page-link disabled">{{ $element }}</span>
      @endif

      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <span class="page-link active" aria-current="page">{{ $page }}</span>
          @else
            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach

    @if ($paginator->hasMorePages())
      <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next ›</a>
    @else
      <span class="page-link disabled">Next ›</span>
    @endif
  </div>
</nav>
@endif
