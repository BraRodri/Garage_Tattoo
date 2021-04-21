@foreach($blog as $key => $value)
<div class="row portada-blog">
    <div class="col-12 col-lg-4">
        <a href="{{ route('blog.detalle', $value->slug)}}">
            @if($value->image_main)
                <img src="{{ asset($value->image_main) }}" class="img-fluid hvr-shrink" alt=".">

                @else
                <img src="{{ asset('/files/productsGalleries/img/sin-imagen.jpg') }}" class="hvr-shrink img-fluid" style="width: 90%" alt=".">
            @endif
        </a>
    </div>
    <div class="col-12 col-lg-8">
        <h2>BLOG</h2>
        <h3>{{$value->title}}</h3>
        @php
            $dato = strip_tags($value->description);
        @endphp
        <p>{{ substr($dato, 0, 200) }}...</p>
    </div>
</div>
@endforeach
