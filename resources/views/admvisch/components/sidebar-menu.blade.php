<div class="sidebar-menu">

	<div class="sidebar-menu-inner">

		<header class="logo-env">

			<!-- logo -->
			<div class="logo">
				<a href= "#">
					<img src={{ asset('images/logotipo-garage-tattoo.png') }} width="120" alt="" />
				</a>
			</div>

			<!-- logo collapse icon -->
			<div class="sidebar-collapse">
				<a href="#" class="sidebar-collapse-icon"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
					<i class="entypo-menu"></i>
				</a>
			</div>


			<!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
			<div class="sidebar-mobile-menu visible-xs">
				<a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
					<i class="entypo-menu"></i>
				</a>
			</div>

		</header>

		<ul id="main-menu" class="main-menu">
			<!-- add class "multiple-expanded" to allow multiple submenus to open -->
			<!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->

			<li >
				<a href= {{ route('index') }}>
					<i class="fa fa-home" aria-hidden="true"></i>
					<span class="title">Home</span>
				</a>
			</li>

            <!-- AGREGAR AQUI LOS PERMISOS O POLICIES DE ROLES  JT-->

			@can('orders.ver', Model::class)
			<li>
				<a href= {{ route('orders') }}>
					<i class="fa fa-list-alt" aria-hidden="true"></i>
					<span class="title">Ventas</span>
				</a>
			</li>
			@endcan


                <!-- FIN DE LOS PERMISOS O POLICIES DE ROLES JT-->
                <!-- ESTO MISMO SE HACE PARA TODOS LOS LI JT-->


				@can('saleStatistics.ver', Model::class)
				<li >
					<a href="{{route('saleStatistics')}}">
						<i class="fa fa-bar-chart" aria-hidden="true"></i>
						<span class="title">Estadísticas de Ventas</span>
					</a>
				</li>
				@endcan



                @php /*
				@can('cotizaciones.ver')
				<li>
                    <a href="{{route('cotizaciones')}}">
                        <i class="fa fa-list-alt" aria-hidden="true"></i>
                        <span class="title">Cotizaciones</span>
                    </a>
                </li>
				@endcan
                */ @endphp

                <li >
                    <a href="{{route('modals')}}">
                        <i class="fa fa-image" aria-hidden="true"></i>
                        <span class="title">Avisos</span>
                    </a>
                </li>


                <li>
					<a href="{{route('publicities')}}">
						<i class="fa fa-image" aria-hidden="true"></i>
						<span class="title">Alerta Promociones</span>
					</a>
				</li>


				<li >
					<a href="{{route('sliders')}}">
						<i class="fa fa-film" aria-hidden="true"></i>
						<span class="title">Slider Principal</span>
					</a>
				</li>


                <li>
                    <a href="{{route('slidersClients')}}">
                        <i class="fa fa-clone" aria-hidden="true"></i>
                        <span class="title">Slider Clientes</span>
                    </a>
                </li>



                <li>
                    <a href="{{route('slidersPartners')}}">
                        <i class="fa fa-columns" aria-hidden="true"></i>
                        <span class="title">Slider Promociones</span>
                    </a>
                </li>


				@can('pages.agregar')
				<li >
					<a href="{{route('pages')}}">
						<i class="fa fa-text-height" aria-hidden="true"></i>
						<span class="title">Páginas</span>
					</a>
				</li>
				@endcan


                <li>
                    <a href="{{route('admin.blog')}}">
                        <i class="fa fa-bold" aria-hidden="true"></i>
                        <span class="title">Blog</span>
                    </a>
                </li>



			<?php /*?>
			<?php if($this->_acl->accessMenu('messages')){ ?>
				<li <?php echo Application\Helper::addActiveSidebarMenu('messages'); ?>>
					<a href="<?php echo URL_FRIENDLY_BASE; ?>messages">
						<i class="fa fa-text-height" aria-hidden="true"></i>
						<span class="title">Respuestas Correos</span>
					</a>
				</li>
			<?php } ?>
 			<?php

			 */?>

                @php
                    /*
                @can('faqs.ver')
                <li>
                    <a href="{{route('faqs')}}">
                        <i class="fa fa-list-ol" aria-hidden="true"></i>
                        <span class="title">Preguntas Frecuentes</span>
                    </a>
                </li>
                @endcan

				<li>
					<a href="{{route('responses')}}">
						<i class="fa fa-text-height" aria-hidden="true"></i>
						<span class="title">Respuestas Páginas</span>
					</a>
				</li>

                <li>
                    <a href="{{route('offices')}}">
                        <i class="fa fa-map" aria-hidden="true"></i>
                        <span class="title">Sucursales</span>
                    </a>
                </li>
                */
                @endphp

				<li class="has-sub">
					<a href="#">
						<i class="fa fa-location-arrow" aria-hidden="true"></i>
						<span class="title">Geolocalización Despachos</span>
					</a>
					<ul>

							<li>
								<a href="{{route('regions')}}">
									<span class="title">Regiones</span>
								</a>
							</li>


							<li>
								<a href="{{route('provinces')}}">
									<span class="title">Provincias</span>
								</a>
							</li>

							<li>
								<a href="{{route('locations')}}">
									<span class="title">Comunas</span>
								</a>
							</li>

					</ul>
				</li>


			<li class="has-sub">
				<a href="#">
					<i class="fa fa-users" aria-hidden="true"></i>
					<span class="title">Gestión de Clientes</span>
				</a>
				<ul>

					<li>
						<a href="{{route('clients')}}">
							<span class="title">Clientes</span>
						</a>
					</li>

                        <li>
                            <a href="{{route('clients.import')}}">
                                <span class="title">Importar Clientes</span>
                            </a>
                        </li>

				</ul>
			</li>


			<li class="has-sub">
				<a href="#">
					<i class="fa fa-shopping-cart" aria-hidden="true"></i>
					<span class="title">Tienda Virtual</span>
				</a>
				<ul>

                    @php /*
					<li>
						<a href="{{route('brands')}}">
							<span class="title">Marcas</span>
						</a>
					</li>
                    */ @endphp

					<li>
						<a href="{{route('categories')}}">
							<span class="title">Categorías</span>
						</a>
					</li>


					<li>
						<a href="{{route('products')}}">
							<span class="title">Productos</span>
						</a>
					</li>

                    <li>
						<a href="{{route('attributes')}}">
							<span class="title">Atributos</span>
						</a>
					</li>

                    <li>
						<a href="{{route('config.dispatch')}}">
							<span class="title">Configuración Despacho</span>
						</a>
					</li>

					@can('products.import')
					<li>
						<a href="{{route('products.import')}}">
							<span class="title">Importar Productos</span>
						</a>
					</li>
					@endcan

					@can('products.importGallerie')
						<li>
							<a href="{{route('products.importGalleries')}}">
								<span class="title">Importar Galería</span>
							</a>
						</li>
					@endcan
				</ul>
			</li>

                <li>
                    <a href="{{route('discounts')}}">
                        <i class="fa fa-money" aria-hidden="true"></i>
                        <span class="title">Cupón de Descuento</span>
                    </a>
                </li>


				<li>
					<a href="{{route('couriers')}}">
						<i class="fa fa-truck" aria-hidden="true"></i>
						<span class="title">Empresas Despacho</span>
					</a>
				</li>

				@can('contacts.ver')
				<li>
					<a href="{{route('contacts')}}">
						<i class="fa fa-envelope-o" aria-hidden="true"></i>
						<span class="title">Contacto</span>
					</a>
				</li>
				@endcan



			<li class="has-sub">
				<a href="#">
					<i class="fa fa-fw fa-cog" aria-hidden="true"></i>
					<span class="title">Configuración Global</span>
				</a>
				<ul>

					@can('configurations.editar')
					<li>
						<a href="{{route('configurations.edit',1)}}">
							<span class="title">Parámetros Generales</span>
						</a>
					</li>
					@endcan

					@can('metadata.editar')
					<li>
						<a href="{{route('metadata.edit',1)}}">
							<span class="title">Metadatos</span>
						</a>
					</li>
					@endcan


					<li>
						<a href="{{route('roles')}}">
							<span class="title">Roles</span>
						</a>
					</li>

					<li>
						<a href="{{route('users')}}">
							<span class="title">Usuarios</span>
						</a>
					</li>

				</ul>
			</li>

		</ul>

	</div>

</div>
