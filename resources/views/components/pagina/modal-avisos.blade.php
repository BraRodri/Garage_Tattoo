@php $ite = 0 @endphp

@if(count($info)>0)

    <div id="myModal" class="modal fade pop-up-portada">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" style="z-index: 999;">&times;</button>

                    <div id="carouselExample" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">

                            @foreach($info as $key => $value)
                                @if($value->active == 1)
                                    <div class="carousel-item {{$ite == 0?'active':''}}">
                                        <img src="{{asset($value->image)}}" class="img-fluid" alt="{{$value->description}}">
                                    </div>
                                @endif
                                @php $ite ++ @endphp
                            @endforeach

                        </div>
                        <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                          <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                          <span class="sr-only">Next</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endif
