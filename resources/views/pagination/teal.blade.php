@if ($paginator->hasPages())
    <nav aria-label="Phân trang kết quả tìm kiếm bạn bè" class="flex justify-center space-x-2 mt-10">

        {{-- Nút trang trước --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 rounded border border-teal-600 text-teal-600 cursor-not-allowed">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" 
               class="px-3 py-1 rounded border border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white">
               ‹
            </a>
        @endif

        {{-- Các trang --}}
        @foreach ($elements as $element)
            {{-- Dấu "..." --}}
            @if (is_string($element))
                <span class="px-3 py-1 text-gray-500">{{ $element }}</span>
            @endif

            {{-- Số trang --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1 rounded bg-teal-600 text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" 
                           class="px-3 py-1 rounded border border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white">
                           {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Nút trang sau --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" 
               class="px-3 py-1 rounded border border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white">
               ›
            </a>
        @else
            <span class="px-3 py-1 rounded border border-teal-600 text-teal-600 cursor-not-allowed">›</span>
        @endif

    </nav>
@endif
