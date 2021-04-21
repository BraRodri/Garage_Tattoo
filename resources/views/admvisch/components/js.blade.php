<!-- Imported styles on this page -->
<link rel="stylesheet" href={{ asset('assets/js/select2/select2-bootstrap.css') }}>
<link rel="stylesheet" href={{ asset('assets/js/select2/select2.css') }}>
<link rel="stylesheet" href={{ asset('assets/js/datatables/datatables.css') }}>
<link rel="stylesheet" href={{ asset('assets/js/multiselect/bootstrap-multiselect.css') }}>
<link rel="stylesheet" href={{ asset('assets/js/uploadfile/uploadfile.css') }}>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Bottom scripts (common) -->
<script src={{ asset('assets/js/datatables/datatables.js') }}></script>

<script src={{ asset('assets/js/bootstrap-tagsinput.min.js') }}></script>
<script src={{ asset('assets/js/gsap/TweenMax.min.js') }}></script>
<script src={{ asset('assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js') }}></script>
<script src={{ asset('assets/js/bootstrap.js') }}></script>
<script src={{ asset('assets/js/joinable.js') }}></script>
<script src={{ asset('assets/js/resizeable.js') }}></script>
<script src={{ asset('assets/js/neon-api.js') }}></script>
<script src={{ asset('assets/js/select2/select2.min.js') }}></script>
<script src={{ asset('assets/js/ckeditor4/ckeditor.js') }}></script>
<script src={{ asset('assets/js/ckeditor4/adapters/jquery.js') }}></script>
<script src={{ asset('assets/js/bootstrap-datepicker.js') }}></script>
<script src={{ asset('assets/js/bootstrap-timepicker.min.js') }}></script>
<script src={{ asset('assets/js/neon-login.js') }}></script>

<script src={{ asset('assets/js/jquery.prettynumber.js') }}></script>

<!-- JavaScripts initializations and stuff -->
<script src={{ asset('assets/js/neon-custom.js') }}></script>

<!-- Imported scripts on this page -->
<script src={{ asset('assets/js/jquery.validate.min.js') }}></script>
<script src={{ asset('assets/js/validate/localization/messages_es.js') }}></script>
<script src={{ asset('assets/js/bootstrap-switch.min.js') }}></script>
<script src={{ asset('assets/js/fileinput.js') }}></script>
<script src={{ asset('assets/js/multiselect/bootstrap-multiselect.js') }}></script>

<script src={{ asset('assets/js/searchable-option-list/sol.js') }}></script>

<!-- Demo Settings -->
<script src={{ asset('assets/js/neon-demo.js') }}></script>

<script type="text/javascript">
$('.modal-child').on('show.bs.modal', function () {
    var modalParent = $(this).attr('data-modal-parent');
    $(modalParent).css('opacity', 0);
});

$('.modal-child').on('hidden.bs.modal', function () {
    var modalParent = $(this).attr('data-modal-parent');
    $(modalParent).css('opacity', 1);
    $('body').addClass('modal-open');
});
</script>

<script src={{ asset('assets/js/duallistbox-bootstrap/jquery.bootstrap-duallistbox.js') }}></script>
<!--<script src="assets/js/duallistbox/dual-list-box.js"></script>-->

<!-- FANCYBOX 3 -->
<script type="text/javascript" src={{ asset('assets/js/fancybox/jquery.fancybox.js') }}></script>
<script type="text/javascript">
    $('[data-fancybox]').fancybox({
        image: {
            protect: true
        },
        loop: false,
        speed: 320,
        slideShow: false
    });
</script>
<!-- END -->

<script src={{ asset('assets/js/modal-alert/bootbox.min.js') }}></script>

<script type="text/javascript" src={{ asset('assets/js/datatables/i18n/spanish.js') }}></script>
<script type="text/javascript" src={{ asset('assets/js/neon-custom-extra.js') }}></script>

<script type="text/javascript" src={{ asset('assets/js/uploadfile/jquery.uploadfile.min.js') }}></script>

<script type="text/javascript">

(function($) {
$.get = function(key)   {
key = key.replace(/[\[]/, '\\[');
key = key.replace(/[\]]/, '\\]');
var pattern = "[\\?&]" + key + "=([^&#]*)";
var regex = new RegExp(pattern);
var url = unescape(window.location.href);
var results = regex.exec(url);
if (results === null) {
return null;
} else {
return results[1];
}
}
})(jQuery);

</script>

<script type="text/javascript" src={{ asset('assets/js/selectpicker/bootstrap-select.js') }}></script>
<script type="text/javascript">

jQuery( document ).ready( function( $ ) {

$.fn.loadDataNotifications = function(){
    var url = "{{route('notifications')}}";

    $.ajax({
        type: "GET",
        encoding:"UTF-8",
        url: url,
        dataType:'json',
        success: function(response){
            $('.notification-orders .badge-info').html(response.number_orders);
            $('.notification-cotizaciones .badge-warning').html(response.number_cotizaciones);
            $('.notification-contacts .badge-secondary').html(response.content_contacts);
            $('.notification-suscriptions .badge-warning').html(response.number_suscriptions);
        },
        error: function() {
            $('.notification-orders .badge-info').html(0);
            $('.notification-cotizaciones .badge-warning').html(0);
            $('.notification-contacts .badge-secondary').html(0);
            $('.notification-suscriptions .badge-warning').html(0);
        }
    });
};

$('body').loadDataNotifications();

});
    $('.datepicker, .datepicker-default').datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        startView: 0,
        weekStart: 1
    });

    // SELECTPICKER PEQUEÑO
    $('.selectpicker-sm').selectpicker({
        liveSearch: false,
        style: "btn-default btn-sm input-sm"
    });
    // SELECTPICKER FECHAS
    $('.selectpicker').selectpicker({
        liveSearch: true,
        style: "btn-select-search"
    });

    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    $('.datepicker-start-daysBeforeDisabled').datepicker({
        format: 'dd-mm-yyyy',
        startDate: now,
        language: 'es',
        startView: 0,
        weekStart: 1
    });
    $('.datepicker-end-daysBeforeDisabled').datepicker({
        format: 'dd-mm-yyyy',
        startDate: now,
        language: 'es',
        startView: 0,
        weekStart: 1
    });

    var checkin = $('.datepicker-start-daysBeforeDisabled').datepicker().on('changeDate', function(ev) {
        if (ev.date.valueOf() > checkout.date.valueOf()) {
            var startdate = new Date(ev.date);
            var newDate = new Date(ev.date);
            newDate.setDate(newDate.getDate() + 1);
            checkout.setDate(startdate);
            checkout.setStartDate(startdate);
        }
        checkin.hide();
        $('.datepicker-end-daysBeforeDisabled').focus();
    }).data('datepicker');

    var checkout = $('.datepicker-end-daysBeforeDisabled').datepicker().on('changeDate', function(ev) {
        checkout.hide();
    }).data('datepicker');

</script>

<script type="text/javascript" src={{ asset('assets/js/autocomplete/jquery-ui.1.11.3.js') }}></script>
{{$slot}}
