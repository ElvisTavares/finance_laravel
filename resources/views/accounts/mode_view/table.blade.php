@php
    $isNowYear = date('Y') == $period->actual->year;
@endphp
<ul class="nav nav-tabs">
    @foreach ($period->years as $year)
        <li class="nav-item primary-color">
            <a class="nav-link {{ $period->actual->year == $year ? 'active' : ''}}"
               href="{{ url("/accounts?year=".$year) }}">{{ $year }}</a>
        </li>
    @endforeach
</ul>
<div class="table-responsive">
    <table class="table table-sm">
        <thead>
            @include('accounts.mode_view.table.thead')
        </thead>
        <tbody>
            @include('accounts.mode_view.table.tbody')
        </tbody>
        <tfoot>
            @include('accounts.mode_view.table.tfoot')
        </tfoot>
    </table>
</div>