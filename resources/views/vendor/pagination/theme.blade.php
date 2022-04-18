@if ($paginator->hasPages())
<div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex flex-wrap mr-3">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
            <a href="javascript:;" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1">
                <i class="ki ki-bold-double-arrow-back icon-xs"></i>
            </a>
            @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1">
                <i class="ki ki-bold-arrow-back icon-xs"></i>
            </a>
            @endif
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="btn btn-icon btn-sm border-0 btn-hover-primary active mr-2 my-1 disabled">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <a href="javascript:;" class="btn btn-icon btn-sm border-0 btn-hover-primary active mr-2 my-1">{{ $page }}</span></a>
                        @else
                            <a href="{{ $url }}" class="btn btn-icon btn-sm border-0 btn-hover-primary mr-2 my-1">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1">
                <i class="ki ki-bold-arrow-next icon-xs"></i>
            </a>
            @else
            <a href="javascript:;" class="btn btn-icon btn-sm btn-light-primary mr-2 my-1">
                <i class="ki ki-bold-double-arrow-next icon-xs"></i>
            </a>
            @endif
        </div>
        <div class="d-flex align-items-center">
            <select class="form-control form-control-sm text-primary font-weight-bold mr-4 border-0 bg-light-primary" style="width: 75px;">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-muted">Дэлгэцэнд {{ $paginator->total }} аас 230 бичлэг</span>
        </div>
    </div>
@endif
