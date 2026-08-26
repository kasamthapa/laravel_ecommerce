{{--
    Custom pagination view, used ONLY by the /shop catalog
    ($products->links('vendor.pagination.luma-catalog')) — Laravel's stock
    tailwind.blade.php view is hardcoded gray-500/blue-600/indigo, shared
    globally with admin, wishlist, and account-orders pagination. Publishing
    it would have restyled those too, so this is a separately-named view
    pointed at from just this one call site instead.

    Small rounded-full buttons for page numbers/prev-next, matching the
    shared button system's pill shape; hairline border for the default
    state, a thin accent border (not a fill) for the current page — same
    "selected state is an outline, not a solid accent fill" rule used for
    the filter sidebar.
--}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center gap-2">
        @if ($paginator->onFirstPage())
            <span class="motion-invert grid h-9 w-9 place-items-center rounded-full border border-hairline text-smoke-dim" aria-hidden="true">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="motion-invert grid h-9 w-9 place-items-center rounded-full border border-hairline text-bone hover:border-volt hover:text-volt" aria-label="{{ __('pagination.previous') }}">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-1 text-sm text-smoke-dim" aria-hidden="true">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="grid h-9 w-9 place-items-center rounded-full border border-volt text-sm font-medium text-bone">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="motion-invert grid h-9 w-9 place-items-center rounded-full border border-hairline text-sm text-smoke hover:border-volt hover:text-volt" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="motion-invert grid h-9 w-9 place-items-center rounded-full border border-hairline text-bone hover:border-volt hover:text-volt" aria-label="{{ __('pagination.next') }}">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
            </a>
        @else
            <span class="motion-invert grid h-9 w-9 place-items-center rounded-full border border-hairline text-smoke-dim" aria-hidden="true">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
            </span>
        @endif
    </nav>
@endif
