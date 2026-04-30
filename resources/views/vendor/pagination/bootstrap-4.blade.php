@if ($paginator->hasPages())
    @php
        $start = max($paginator->currentPage() - 1, 1);
        $end = min($paginator->currentPage() + 1, $paginator->lastPage());
    @endphp

    <ul class="pagination justify-content-center">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link">«</span></li>
        @else
            <li class="page-item">
                <a class="page-link" wire:click="previousPage('{{ $paginator->getPageName() }}')">«</a>
            </li>
        @endif

        {{-- First --}}
        @if ($start > 1)
            <li class="page-item">
                <a class="page-link" wire:click="gotoPage(1, '{{ $paginator->getPageName() }}')">1</a>
            </li>

            @if ($start > 2)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif
        @endif

        {{-- Middle --}}
        @for ($i = $start; $i <= $end; $i++)
            <li class="page-item {{ $paginator->currentPage() == $i ? 'active' : '' }}">
                <a class="page-link" wire:click="gotoPage({{ $i }}, '{{ $paginator->getPageName() }}')">
                    {{ $i }}
                </a>
            </li>
        @endfor

        {{-- Last --}}
        @if ($end < $paginator->lastPage())
            @if ($end < $paginator->lastPage() - 1)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif

            <li class="page-item">
                <a class="page-link"
                    wire:click="gotoPage({{ $paginator->lastPage() }}, '{{ $paginator->getPageName() }}')">
                    {{ $paginator->lastPage() }}
                </a>
            </li>
        @endif

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" wire:click="nextPage('{{ $paginator->getPageName() }}')">»</a>
            </li>
        @else
            <li class="page-item disabled"><span class="page-link">»</span></li>
        @endif

    </ul>
@endif