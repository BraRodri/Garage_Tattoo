<x-app-layoutt>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    <ol class="breadcrumb bc-2">
        <li>
            <a href=""><i class="entypo-home"></i>Home</a>
        </li>
        <li class="active">
            <strong>Home</strong>
        </li>
    </ol>

    <div class="row">
        <div class="col-md-12">
            <div class="well">
                <h1>Bienvenido</h1>
                <blockquote>
                    <p>Al panel de Administración de Contenidos de su Página Web. El cual separa el contenido actual de
                        su sitio web y lo almacena en una base de datos, para poder ser administrado posteriormente de
                        manera ágil y sencilla. Permite manipular textos, diseño, publicación de contenidos privados,
                        enlaces, menús y mucho más.</p>

                        <p>
                            El root es:
                            <?php echo ROOT; ?>
                        </p>
                </blockquote>
                <hr>
                <h4>Características:</h4>
                <br />
                <ul>
                    <li>Estadísticas de ventas, productos más y menos vendidos.</li>
                    <li>Gestión de pedidos.</li>
                    <li>Publicación de contenidos sin limitaciones.</li>
                    <li>Administración de usuarios, para controlar el ingreso al administrador.</li>
                    <li>Administración de metadatos, para el correcto posicionamiento web.</li>
                    <li>Editor para dar formatos y estilos a los textos e imágenes.</li>
                </ul>
                <br>

                <div class="alert alert-info">
                    Si necesitas ayuda, envíanos un correo electrónico a: <a
                        href="mailto:soporte@visualchile.cl">soporte@visualchile.cl</a>, indicándonos tu consulta. Atte,
                    <strong>Visual Chile</strong>.
                </div>
            </div>
        </div>
    </div>

    <br />

    <x-slot name="js"></x-slot>

</x-app-layoutt>
