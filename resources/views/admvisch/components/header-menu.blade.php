<div class="row hidden-print">

    <div class="col-md-6 col-sm-8 clearfix">

        <ul class="user-info pull-left pull-none-xsm">

            <!-- Profile Info -->
            <li class="profile-info dropdown">

                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src={{ asset('assets/images/user_male.png') }} alt="" class="img-circle" width="44" />
                    <small>{{ Auth::user()->name }} / {{ Auth::user()->name }}</small>
                </a>

                <ul class="dropdown-menu">

                    <!-- Reverse Caret -->
                    <li class="caret"></li>

                    <!-- Profile sub-links -->
                    <li>
                        <a href="#">
                            <i class="entypo-user"></i>
                            Editar Perfil
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="entypo-logout right"></i>
                            Salir
                        </a>
                    </li>
                </ul>

            </li>

        </ul>

        <ul class="user-info pull-left pull-right-xs pull-none-xsm">

            <!-- Raw Notifications -->
            <li class="notifications dropdown notification-orders">

                <a href="#">
                    <i class="entypo-basket"></i>
                    <span class="badge badge-info">0</span>
                </a>

            </li>

            <!-- Task Notifications -->
            <li class="notifications dropdown notification-cotizaciones">

                <a href="#">
                    <i class="entypo-list"></i>
                    <span class="badge badge-warning">0</span>
                </a>

            </li>

            <!-- Message Notifications -->
            <li class="notifications dropdown notification-contacts">

                <a href="#">
                    <i class="entypo-mail"></i>
                    <span class="badge badge-secondary">0</span>
                </a>

            </li>

            <!-- Task Notifications -->
            <?php
            /*?>
            <li class="notifications dropdown notification-suscriptions">

                <a href="<?php echo URL_FRIENDLY_BASE; ?>suscriptions">
                    <i class="entypo-list"></i>
                    <span class="badge badge-warning">0</span>
                </a>

            </li>
            <?php */
            ?>

        </ul>

    </div>

    <!-- Raw Links -->
    <div class="col-md-6 col-sm-4 clearfix">

        <ul class="list-inline links-list pull-right">

            <!-- Language Selector -->
            <li>
                <a href="#" target="_blank" class="btn btn-success btn-small">
                    Ir a Web <i class="fa fa-external-link" aria-hidden="true"></i>
                </a>
            </li>
            <li>


                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a class="btn btn-primary btn-small" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    this.closest('form').submit();">
                        {{ __('Logout') }} Salir <i class="entypo-logout right"></i>
                    </a>
                </form>


            </li>
        </ul>

    </div>

</div>
