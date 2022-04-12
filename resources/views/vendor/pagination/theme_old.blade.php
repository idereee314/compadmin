@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <a href="javascript:;" class="prevposts-link disabled"><i class="fas fa-caret-left"></i><span>@lang('pagination.previous')</span></a>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="prevposts-link"><i class="fas fa-caret-left"></i><span>@lang('pagination.previous')</span></a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="disabled">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a href="javascript:;" class="current-page">{{ $page }}</span></a>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="nextposts-link"><span>@lang('pagination.next')</span><i class="fas fa-caret-right"></i></a>
        @else
            <a href="javascript:;" class="nextposts-link disabled"><span>@lang('pagination.next')</span><i class="fas fa-caret-right"></i></a>
        @endif
    </div>
@endif
