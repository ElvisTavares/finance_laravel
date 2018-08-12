@foreach($links as $link)
    {!! $link->html(count($links)) !!}
@endforeach