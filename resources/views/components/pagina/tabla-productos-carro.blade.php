@if(count($carro)>0)

    <form action="{{ route('micarro.update') }}" method="POST" id="form1">
    @csrf

    @foreach ($carro as $detail)

        <div class="r-carro-productos">
            <div class="row align-items-center">
                <div class="col-xs-2 col-sm-2 col-md-3">
                    <a href="{{route('detalle.producto', $detail->associatedModel->slug)}}">
                        @if (count($detail->attributes['galleries'])>0)
                            <img src="{{asset('/files/productsGalleries/img/' . $detail->attributes['galleries'][0]['image'])}}" class="img-fluid" alt=".">
                            @else
                            <img src="{{asset('/files/productsGalleries/img/sin-imagen.jpg')}}" class="img-fluid" alt=".">
                        @endif
                    </a>
                </div>
                <div class="col-xs-10 col-sm-10 col-md-9">

                        <div class="d-flex justify-content-between">
                            <h2>{{ $detail->name }}</h2>
                            <span>${{ convertirValor($detail->price) }}</span>
                        </div>
                        <div class="">
                            <p>CÓDIGO: {{$detail->associatedModel->sku}}</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <input type="number" name="quantity[]" id="" value="{{$detail->quantity}}" min="1" class="form-control mr-2" style="width: 60px">
                            <a href="{{route('cart_details.destroy',$detail->id)}}" class="btn btn-outline-secondary "><i class="fa fa-trash-o"></i> </a>
                        </div>

                </div>

            </div>
        </div>

        <hr>

    @endforeach

        <div class="text-right">
            <input type="submit" id="actualizar" class="btn btn-warning" value="ACTUALIZAR CARRITO">
        </div>

        <hr>

    </form>

@endif
