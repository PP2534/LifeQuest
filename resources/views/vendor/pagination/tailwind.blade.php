@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex justify-center items-center space-x-2">
        {{-- First Page Link --}}
        @if ($paginator->onFirstPage())
            <button class="px-3 py-1 rounded border border-teal-600 text-teal-600 opacity-50 cursor-not-allowed" aria-label="Trang đầu tiên" disabled>
                &laquo;&laquo; Đầu
            </button>
        @else
            <a href="{{ $paginator->url(1) }}" wire:navigate class="px-3 py-1 rounded border border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-teal-400" aria-label="Trang đầu tiên">
                &laquo;&laquo; Đầu
            </a>
        @endif

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <button class="px-3 py-1 rounded border border-teal-600 text-teal-600 opacity-50 cursor-not-allowed" aria-label="Trang trước" disabled>
                &laquo; Trước
            </button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" wire:navigate class="px-3 py-1 rounded border border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-teal-400" aria-label="Trang trước">
                &laquo; Trước
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-3 py-1 rounded border border-teal-600 text-teal-600">
                    {{ $element }}
                </span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <button class="px-3 py-1 rounded bg-teal-600 text-white focus:outline-none focus:ring-2 focus:ring-teal-400" aria-current="page">
                            {{ $page }}
                        </button>
                    @else
                        <a href="{{ $url }}"  wire:navigate class="px-3 py-1 rounded border border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-teal-400">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"  wire:navigate class="px-3 py-1 rounded border border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-teal-400" aria-label="Trang tiếp theo">
                Tiếp &raquo;
            </a>
        @else
            <button class="px-3 py-1 rounded border border-teal-600 text-teal-600 opacity-50 cursor-not-allowed" aria-label="Trang tiếp theo" disabled>
                Tiếp &raquo;
            </button>
        @endif

        {{-- Last Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->url($paginator->lastPage()) }}" wire:navigate class="px-3 py-1 rounded border border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-teal-400" aria-label="Trang cuối cùng">
                Cuối &raquo;&raquo;
            </a>
        @else
            <button class="px-3 py-1 rounded border border-teal-600 text-teal-600 opacity-50 cursor-not-allowed" aria-label="Trang cuối cùng" disabled>
                Cuối &raquo;&raquo;
            </button>
        @endif
    </nav>
@endif
