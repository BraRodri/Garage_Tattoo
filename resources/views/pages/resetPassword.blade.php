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
                <div class="col-md-8 mt-2 mb-2 card-user">
                    <div class="card">
                        <div class="card-header text-center"><?=$PaginaTitulo?></div>
                        <div class="card-body">
                           <p class="text-center mb-5">Ingresa tus datos para realizar tu solicitud</p>
                            <form action="" method="">
                                <div class="form-group row">
                                    <label for="email_address" class="col-md-4 col-form-label text-md-right">Usuario/Email</label>
                                    <div class="col-md-6">
                                        <input type="text" id="email_address" class="form-control" name="email-address" required autofocus>
                                    </div>
                                </div>
                                <div class="col-md-6 offset-md-4">
                                    <a href="recuperar-clave.php" class="btn btn-warning">Recuperar Clave</a>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </main>

    </div>

    <x-slot name="js">
    </x-slot>

</x-app-layout>

