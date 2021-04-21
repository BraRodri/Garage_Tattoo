@php
    $ite = 0;
    $ite_2 = 0;
@endphp

<div id="carouselExampleIndicators" class="carousel slide slider-principal" data-ride="carousel"> <!-- CLASS="carousel-fade" animar con FADE -->
    <ol class="carousel-indicators">
        @foreach($slider_home as $key => $value)
            <li data-target="#carouselExampleIndicators" data-slide-to="{{$ite_2}}" class="{{$ite_2 == 0?'active':''}}"></li>
            @php $ite_2 ++ @endphp
        @endforeach
    </ol>
    <div class="carousel-inner">

        @foreach($slider_home as $key => $value)
            <div class="carousel-item {{$ite == 0?'active':''}}">
                <img src="{{asset($value->image)}}" class="d-block w-100" alt="{{$value->title}}">
            </div>
            @php $ite ++ @endphp
        @endforeach

    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
</div>
