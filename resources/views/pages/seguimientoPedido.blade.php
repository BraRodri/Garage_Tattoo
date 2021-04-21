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
                           <p class="text-center mb-5">Para realizar el seguimiento de tu pedido, ingresa el ID de tu pedido en la casilla inferior y has click en "Seguir". Este número puedes encontrarlo en el correo de confirmación de compra que te enviamos.</p>
                            <form action="" method="">
                                <div class="form-group row">
                                    <label for="email_address" class="col-md-4 col-form-label text-md-right">Número o ID del Pedido</label>
                                    <div class="col-md-6 mb-3">
                                        <input type="text" id="id-pedido" class="form-control" name="id-pedido" placeholder="Figura en tu correo de confirmación de compra" required autofocus>
                                    </div>
                                    <label for="email_address" class="col-md-4 col-form-label text-md-right">Email del Pedido</label>
                                    <div class="col-md-6">
                                        <input type="email" id="email_address" class="form-control" name="email-address" placeholder="Correo que usaste para realizar el pedido" required autofocus>
                                    </div>
                                </div>
                                <div class="col-md-6 offset-md-4">
                                    <a href="resultado-seguimiento-de-pedido.php" class="btn btn-warning">Hacer Seguimiento</a>

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
