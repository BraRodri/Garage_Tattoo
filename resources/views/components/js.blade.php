
<!-- jQuery first, then Tether, then Bootstrap JS. --> <!-- <script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script> --> <!-- jQuery first, then Tether, then Bootstrap JS. -->
<script src="{{asset('js/jquery.js')}}"></script> <!-- JQuery -->
<script src="{{asset('js/tether.js')}}"></script> <!-- opcional -->

<script src="{{asset('js/popper.min.js')}}"></script><!-- Bootstrap Mensajes Tooltips -->
<script src="{{asset('js/bootstrap.js')}}"></script> <!-- Bootstrap -->
<script src="{{asset('js/backtop.js')}}"></script><!-- BackTop -->

<!-- uso de COOKIES -->
<!-- COOKIE -->
<script src="{{asset('js/cookiealert.js')}}"></script>
    <!-- SI ES NECESARIO QUE APAREZCA UN MENSAJE QUE SE ACEPTÓ SE HABILITA LO SIGUIENTE
    <script>
        window.addEventListener("cookieAlertAccept", function() {
            alert("cookies accepted")
        })
    </script> -->

<!-- SWIPER JS -->
<script src="{{asset('js/swiper/swiper.min.js')}}"></script> <!-- Swiper Js -->

        <!-- SWIPER Carrusel -->
        <script>
            var swiper1 = new Swiper('.swiper-banerpromo', {
              slidesPerView: 1,
              spaceBetween: 10,
              slidesPerGroup: 1,
              loop: true,
              loopFillGroupWithBlank: true,
              pagination: {
                el: '.swiper-pagination',
                clickable: true,
              },
                autoplay: {
                delay: 6500,
                disableOnInteraction: false,
              },
              navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
              },
              breakpoints: {
                180: {
                  slidesPerView: 1,
                  spaceBetween: 20,
                },

                640: {
                  slidesPerView: 1,
                  spaceBetween: 20,
                },
                768: {
                  slidesPerView: 1,
                  spaceBetween: 40,
                },
                1024: {
                  slidesPerView: 1,
                  spaceBetween: 10,
                },
              }
            });

            var swiper1 = new Swiper('.swiper-nuestrosclientes', {
              slidesPerView: 2,
              spaceBetween: 10,
              slidesPerGroup: 1,
              loop: true,
              loopFillGroupWithBlank: true,
              pagination: {
                el: '.swiper-pagination',
                clickable: true,
              },
                autoplay: {
                delay: 6500,
                disableOnInteraction: false,
              },
              navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
              },
              breakpoints: {
                180: {
                  slidesPerView: 2,
                  spaceBetween: 20,
                },

                640: {
                  slidesPerView: 4,
                  spaceBetween: 20,
                },
                768: {
                  slidesPerView: 4,
                  spaceBetween: 10,
                },
                1024: {
                  slidesPerView: 6,
                  spaceBetween: 10,
                },
              }
            });

            var swiper1 = new Swiper('.swiper-nuestrasofertas', {
              slidesPerView: 4,
              spaceBetween: 10,
              slidesPerGroup: 1,
              loop: true,
              loopFillGroupWithBlank: true,
              pagination: {
                el: '.swiper-pagination',
                clickable: true,
              },
                autoplay: {
                delay: 3400,
                disableOnInteraction: false,
              },
              navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
              },
              breakpoints: {
                180: {
                  slidesPerView: 1,
                  spaceBetween: 20,
                },

                640: {
                  slidesPerView: 2,
                  spaceBetween: 20,
                },
                768: {
                  slidesPerView: 2,
                  spaceBetween: 40,
                },
                1024: {
                  slidesPerView: 4,
                  spaceBetween: 10,
                },
              }
            });

            var swiper1 = new Swiper('.swiper-relacionados', {
              slidesPerView: 4,
              spaceBetween: 10,
              slidesPerGroup: 1,
              loop: true,
              loopFillGroupWithBlank: true,
              pagination: {
                el: '.swiper-pagination',
                clickable: true,
              },
                autoplay: {
                delay: 3400,
                disableOnInteraction: false,
              },
              navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
              },
              breakpoints: {
                180: {
                  slidesPerView: 1,
                  spaceBetween: 20,
                },

                640: {
                  slidesPerView: 2,
                  spaceBetween: 20,
                },
                768: {
                  slidesPerView: 3,
                  spaceBetween: 40,
                },
                1024: {
                  slidesPerView: 4,
                  spaceBetween: 10,
                },
              }
            });

        </script>


<!-- MENÚ IZQUIERDO ACORDEON -->
<link rel="stylesheet" href="{{asset('js/menu-izq/metismenujs.min.css')}}"> <!-- menu-izq -->
<script src="{{asset('js/menu-izq/metismenujs.min.js')}}"></script>
<script>
document.addEventListener("DOMContentLoaded", function(event) {
  new MetisMenu('#menu1', {
    toggle: false
  });
  new MetisMenu('#menu2', {
    toggle: false
  });
});
</script>

<!-- JS BUSCADOR -->
<script src="{{asset('js/extention/choices.js')}}"></script>

    <script>
      const choices = new Choices('[data-trigger]',
      {
        searchEnabled: true,
        loadingText: 'Cargando...',
        shouldSortItems: false,
        shouldSort: false
      });

    </script>



<!-- fancybox -->
<script src="{{asset('js/fancybox-master/jquery.fancybox.min.js')}}"></script>

<!-- PRODUCTO 2 FLEXSLIDER -->
<script src="{{asset('js/flexible-slider/jquery.flexslider.js')}}"></script>
<link rel="stylesheet" href="{{asset('js/flexible-slider/flexslider.css')}}" type="text/css" media="screen" />

    <link type="text/css" rel="stylesheet" href="{{asset('js/flexible-slider/slick.css')}}"/>
    <link type="text/css" rel="stylesheet" href="{{asset('js/flexible-slider/slick-theme.css')}}"/>
    <!-- nouislider -->
     <link type="text/css" rel="stylesheet" href="{{asset('js/flexible-slider/nouislider.min.css')}}"/>
    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="{{asset('js/flexible-slider/style-productos.css')}}"/>

 <!-- FLEXIBLE SLIDER PARA PRODUCTOS -->
        <script src="{{asset('js/flexible-slider/slick.min.js')}}"></script>
        <script src="{{asset('js/flexible-slider/nouislider.min.js')}}"></script>

 <!-- FLEXSLIDER PARA ZOOM IMAGEN -->
        <script>
            jQuery(document).ready(function($){
                $('.flexslider11').flexslider({
                    animation: "slide",
                    controlNav: "thumbnails"
                });
            });
        </script>

{{$slot}}
