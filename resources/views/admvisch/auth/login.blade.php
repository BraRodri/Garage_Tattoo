<!DOCTYPE html>
<html lang="en">
<head>
	<x-metaa/>
	<x-csss/>
</head>
<body class="page-body login-page login-form-fall" data-url="http://neon.dev">

<div class="login-container">

	<div class="login-header">

        <br>
        <br>

		<div class="login-content">

            <br>
            <br>

			<a style="cursor:default" class="logo">
				<img src={{ asset('images/logotipo-garage-tattoo.png') }} width="200" alt="" />
			</a>

			<!-- progress bar indicator -->
			<div class="login-progressbar-indicator">
				<h3>43%</h3>
				<span>cargando...</span>
			</div>
		</div>

	</div>

	<div class="login-progressbar">
		<div></div>
	</div>

	<div class="login-form">

		<div class="login-content login-content-white">

            <div class="form-login-error">
                <h3>Error</h3>
                <p>

                    @error('error')
                        {{$message}}
                    @enderror
                </p>
            </div>

            <form method="POST" action="{{ route('login.admin') }}" id="form-login" role="form">
                @csrf

                <div class="form-group">

                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="entypo-user"></i>
                        </div>

                        <input type="text" class="form-control" name="email" id="username" placeholder="Usuario" autocomplete="off" />
                    </div>

                </div>

                <div class="form-group">

                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="entypo-key"></i>
                        </div>

                        <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" autocomplete="off" />
                    </div>

                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        <i class="entypo-login"></i>
                        Ingresar
                    </button>
                </div>

            </form>

            <div class="login-bottom-links">
                <a href="#">¿Olvidaste tu clave de acceso?</a>
            </div>

		</div>

	</div>

</div>



<x-jss/>


</body>
</html>
