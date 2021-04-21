<!-- modal de categoría de productos de la TIENDA -->
<div class="modal-menu-categorias">
    <div id="modal_aside_right" class="modal fixed-right fade" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-aside" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">MENÚ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="menu-categorias-productos">
                <h2><i class="fa fa-bars"></i> CATEGORÍAS DE PRODUCTOS</h2>
            </div>

            <!-- DESPLIEGA EL MENÚ HACIA EL LADO ID="Menu1" -->
            <nav class="sidebar-nav">

                @if(count($menus) > 0)

                    <ul class="metismenu" id="menu2">

                        @foreach($menus as $key => $value)

                            @if($value->level == 1)

                                @php
                                    $dato1 = 0;
                                    foreach ($menus as $key => $primero) {
                                        $dato1 += $primero->parent_id == $value->id;
                                    }
                                @endphp

                                <li>
                                    <a class="has-arrow" href="{{ route('categoria.producto', $value->slug) }}" aria-expanded="false">
                                        <h2>{{$value->title}}</h2>
                                    </a>

                                    @if($dato1 > 0)

                                        <ul class="mm-collapse">

                                            @foreach($menus as $key => $value2)

                                                @if($value2->level == 2 && $value2->parent_id == $value->id)

                                                    @php
                                                        $dato2 = 0;
                                                        foreach ($menus as $key => $primero2) {
                                                            $dato2 += $primero2->parent_id == $value2->id;
                                                        }
                                                    @endphp

                                                    <li>
                                                        <a class="has-arrow" href="{{ route('categoria.producto', $value2->slug) }}" aria-expanded="false">{{$value2->title}}</a>

                                                        @if($dato2 > 0)
                                                            <ul class="mm-collapse">
                                                                @foreach($menus as $key => $value3 )

                                                                    @if($value3->level == 3 && $value3->parent_id == $value2->id)
                                                                        <li>
                                                                            <a href="{{ route('categoria.producto', $value3->slug) }}">{{$value3->title}}</a>
                                                                        </li>
                                                                    @endif

                                                                @endforeach
                                                            </ul>
                                                        @endif

                                                    </li>

                                                @endif

                                            @endforeach

                                        </ul>

                                    @endif

                                </li>

                            @endif

                        @endforeach

                    </ul>

                @endif

            </nav>


          </div>
        </div>
      </div> <!-- modal-bialog .// -->
    </div> <!-- modal.// -->
</div>
