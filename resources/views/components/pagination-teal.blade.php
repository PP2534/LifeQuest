@if ($paginator->hasPages())
    <nav aria-label="Pagination" class="mt-12 flex justify-center items-center space-x-2">
        
        {{-- Nút "Trước" (Previous) --}}
        @if ($paginator->onFirstPage())
            <button class="px-3 py-1 rounded border border-gray-300 text-gray-400 cursor-not-allowed" disabled>
                &laquo; Trước
            </button>
        @else
            <button wire:click="previousPage" wire:loading.attr="disabled"
                    class="px-3 py-1 rounded border border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-teal-400 transition-colors duration-200">
                &laquo; Trước
            </button>
        @endif

        {{-- Các con số trang (Pagination Elements) --}}
        @foreach ($elements as $element)
            {{-- Dấu ba chấm "..." --}}
            @if (is_string($element))
                <span class="px-3 py-1 rounded border border-gray-300 text-gray-500 cursor-default">{{ $element }}</span>
            @endif

            {{-- Mảng các số trang --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        {{-- Trang hiện tại (Màu nền xanh, chữ trắng) --}}
                        <button class="px-3 py-1 rounded bg-teal-600 text-white focus:outline-none focus:ring-2 focus:ring-teal-400" aria-current="page">
                            {{ $page }}
                        </button>
                    @else
                        {{-- Các trang khác (Viền xanh, chữ xanh) --}}
                        <button wire:click="gotoPage({{ $page }})" 
                                class="px-3 py-1 rounded border border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-teal-400 transition-colors duration-200">
                            {{ $page }}
                        </button>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Nút "Tiếp theo" (Next) --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled"
                    class="px-3 py-1 rounded border border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-teal-400 transition-colors duration-200">
                Tiếp &raquo;
            </button>
        @else
            <button class="px-3 py-1 rounded border border-gray-300 text-gray-400 cursor-not-allowed" disabled>
                Tiếp &raquo;
            </button>
        @endif
    </nav>
@endif