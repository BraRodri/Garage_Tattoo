<x-app-layout>

    @section('pagina')
        <?=$PaginaTitulo?>
    @endsection

    <div class="container">

        <div class="titulo-interiores">
            <h1><?=$PaginaTitulo?></h1>
        </div>

        <!-- página de CONTACTO -->
        <div class="cont-contenedor">
            <div class="row">
                <div class="col-md-5 col-lg-4">

                    <div class="r-contacto-info">

                        <h2><i class="fa fa-phone"></i>  LLÁMANOS</h2>

                        <p><strong>Teléfono:</strong></p>
                        <p><i class="fa fa-phone"></i> <a href="tel:{{$info->phone1}}">{{$info->phone1}}</a></p><br>

                        <p><strong>Whatsapp:</strong></p>
                        <p><img src="{{asset('images/icono-whatsapp.svg')}}" width="15" height="15" alt="whatsappp"> <a href="https://api.whatsapp.com/send?phone={{$info->phone3}}">{{$info->phone3}}</a></p>

                        <h2><i class="fa fa-envelope-o"></i> ESCRÍBENOS</h2>

                        <p>Dudas o consultas sobre nosotros o tus pedidos te respondemos.</p>
                        <br>

                        <p><strong>Correo Contacto</strong></p>
                        <p><a href="mailto:{{$info->email}}">{{$info->email}}</a></p>
                    </div>

                </div>

                <div class="col-md-7 col-lg-8">

                    <div class="r-form-contacto">

                    <h2>FORMULARIO DE CONTACTO</h2>

                    <form>
                            <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputState">Asunto</label>
                                <select id="inputState" class="form-control">
                                <option selected>- Seleccionar -</option>
                                <option>Consultas</option>
                                <option>Contacto</option>
                                <option>Otro</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Nombre y Apellido</label>
                                <input type="email" class="form-control" id="inputEmail4" placeholder="ej: Juan Soto">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Email</label>
                                <input type="email" class="form-control" id="inputEmail4" placeholder="ej: email@mail.com">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Teléfono / Celular</label>
                                <input type="text" class="form-control" id="inputPassword4" placeholder="ej: +569 1234 5678">
                            </div>
                            </div>

                            <div class="form-group">
                            <label for="exampleFormControlTextarea1">Comentarios</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning">Enviar</button>
                            </div>
                    </form>

                </div>

                <div class="col-lg-6">
                    <div class="mapas">
                        <h2>{{$info->city}}</h2>
                        <p>{{$info->address}}</p>
                        <iframe src="{{$info->map_1}}" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mapas">
                        <h2>{{$info->city_2}}</h2>
                        <p>{{$info->address_2}}</p>
                        <iframe src="{{$info->map_2}}" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <x-slot name="js">
    </x-slot>

</x-app-layout>
