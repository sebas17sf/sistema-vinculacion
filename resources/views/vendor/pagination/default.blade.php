<div class="d-flex justify-content-center">
    <ul class="pagination">
        @if ($profesores->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Anterior</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $profesores->previousPageUrl() }}" aria-label="Anterior">Anterior</a>
            </li>
        @endif

        @foreach ($profesores as $page => $url)
            @if ($page == $profesores->currentPage())
                <li class="page-item active">
                    <span class="page-link">{{ $page }}</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endif
        @endforeach

        @if ($profesores->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $profesores->nextPageUrl() }}" aria-label="Siguiente">Siguiente</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Siguiente</span>
            </li>
        @endif
    </ul>
</div>

<div class="d-flex justify-content-center">
    <ul class="pagination">
        @if ($estudiantesVinculacion->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Anterior</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $estudiantesVinculacion->previousPageUrl() }}" aria-label="Anterior">Anterior</a>
            </li>
        @endif

        @foreach ($estudiantesVinculacion->getUrlRange(1, $estudiantesVinculacion->lastPage()) as $page => $url)
            @if ($page == $estudiantesVinculacion->currentPage())
                <li class="page-item active">
                    <span class="page-link">{{ $page }}</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endif
        @endforeach

        @if ($estudiantesVinculacion->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $estudiantesVinculacion->nextPageUrl() }}" aria-label="Siguiente">Siguiente</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Siguiente</span>
            </li>
        @endif
    </ul>
</div>
