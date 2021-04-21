<div class="swiper-container swiper-nuestrosclientes">
    <div class="swiper-wrapper">

        @foreach($clientes as $key => $value)
            <div class="swiper-slide">
                <img src="{{asset($value->image)}}" alt="{{$value->title}}">
            </div>
        @endforeach

    </div>

    <!-- Add Arrows -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>

</div>
