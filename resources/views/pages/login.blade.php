<x-app-layout>

    @section('pagina')
        <?=$PaginaTitulo?>
    @endsection

    <div class="container">

        <div class="titulo-interiores">
            <h1><?=$PaginaTitulo?></h1>
        </div>

        <main class="login-form mt-5 mb-5">
            <div class="row justify-content-center">
                <div class="col-md-6 mt-2 mb-2 card-user">
                    <div class="card">
                        <div class="card-header text-center">ESTOY REGISTRADO </div>
                        <div class="card-body">
                           <p class="text-center mb-5">Iniciar sesión para comprar y ver mis compras</p>
                            <form action="{{ route('login.client') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-3">
                                        <input type="text" id="email" class="form-control" name="email" required autofocus placeholder="Usuario/Correo">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-3">
                                        <input type="password" id="password" class="form-control" name="password" placeholder="Contraseña" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-3">
                                        <div class="checkbox text-center">
                                            <label>
                                                <input type="checkbox" name="remember"> Recuérdame
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                    <div class="col-md-6 offset-md-3  text-center">
                                    <input type="submit" class="btn btn-warning" value="Iniciar Sesión">

                                    <a href="{{route('resetPassword')}}" class="btn text-dark">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2 mb-2 card-user">
                        <div class="card">
                            <div class="card-header text-center">¿NO ESTÁS REGISTRADO?</div>
                            <div class="card-body">
                                <p class="text-center">Regístrate y compra de manera fácil y segura en nuestra tienda virtual de Pluss Store. Aprovecha nuestros descuentos y promociones que tenemos para tí.</p>
                                <div class="text-center mt-5 mb-5">
                                    <a href="{{route('registro')}}" class="btn btn-warning">Registrarme</a>
                                </div>
                        </div>
                    </div>

                </div>
            </div>

        </main>

    </div>
    <x-slot name="js">

    </x-slot>
</x-app-layout>
