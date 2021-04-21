
<nav class="sidebar-nav">

    @if(count($menus) > 0)

        <ul class="metismenu" id="menu1">

            @foreach($menus as $key => $value)

                @if($value->level == 1)

                    @php
                        $dato1 = 0;
                        foreach ($menus as $key => $primero) {
                            $dato1 += $primero->parent_id == $value->id;
                        }
                    @endphp

                    <li id="removable">
                        <a class="has-arrow" href="{{ route('categoria.producto', $value->slug) }}" aria-expanded="false">
                            <h2><img src="{{asset($value->main_image)}}" class="img-fluid" alt="."> {{$value->title}}</h2></h2>
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
