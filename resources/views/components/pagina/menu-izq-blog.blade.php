<div class="panel panel-danger">
    <div class="menu-noticias-mini">
        <h2>ÚLTIMOS ARTÍCULOS</h2>
    </div>
    <div class="list-group menu-noticias-mini">
        @if(count($blog)>0)

            @foreach($blog as $key => $value)
                <a href="{{ route('blog.detalle', $value->slug)}}" class="list-group-item @if (Request::url() == route('blog.detalle', $value->slug)) active @endif">
                    <span>{{$value->date_public}} </span> - {{$value->title}}
                </a>
            @endforeach

            @else
            <a>
                <h6>No hay entradas de noticias actualmente!</h6>
            </a>

        @endif
    </div>
</div>
