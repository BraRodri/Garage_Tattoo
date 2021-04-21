<div class="swiper-container swiper-banerpromo">

    <div class="swiper-wrapper">

        @foreach($promociones as $key => $value)
            <div class="swiper-slide">
                <a href="{{$value->link}}"><img src="{{asset($value->image)}}" class="img-fluid" alt="{{$value->title}}"></a>
            </div>
        @endforeach

    </div>

    <!-- Add Arrows -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>

</div>
