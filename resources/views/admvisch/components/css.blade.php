<link rel="stylesheet" href={{asset('assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css')}}>
<link rel="stylesheet" href={{ asset('assets/css/font-icons/entypo/css/entypo.css') }}>
<link rel="stylesheet" href={{ asset('assets/css/font-icons/font-awesome/css/font-awesome.css') }}>
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
<link rel="stylesheet" href={{ asset('assets/css/bootstrap.css') }}>
<link rel="stylesheet" href={{ asset('assets/css/neon-core.css') }}>
<link rel="stylesheet" href={{ asset('assets/css/neon-theme.css') }}>
<link rel="stylesheet" href={{ asset('assets/css/neon-forms.css') }}>
<link rel="stylesheet" href={{ asset('assets/js/fancybox/jquery.fancybox.css') }}>
<link rel="stylesheet" href={{ asset('assets/js/duallistbox-bootstrap/bootstrap-duallistbox.css') }}>
<link rel="stylesheet" href={{ asset('assets/js/selectpicker/bootstrap-select.min.css') }}>
<link rel="stylesheet" href={{ asset('assets/js/searchable-option-list/sol.css') }}>
<link rel="stylesheet" href={{ asset('assets/css/custom.css') }}>

<script src={{ asset('assets/js/jquery-1.11.3.min.js') }}></script>

<!--[if lt IE 9]><script src="<?php echo BASE_URL_ADMIN; ?>assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<link rel="stylesheet" href={{ asset('assets/js/autocomplete/jquery-ui.css') }}>
<style type="text/css">
	.ui-autocomplete {
		max-height: 100px;
		overflow-y: auto;
		/* prevent horizontal scrollbar */
		overflow-x: hidden;
		z-index:100000;
	}

	.ui-autocomplete-loading {
		background: white url({{ asset('assets/js/autocomplete/ui-anim_basic_16x16.gif') }}) right 5px center no-repeat;
	}

	/* IE 6 doesn't support max-height
     * we use height instead, but this forces the menu to always be this tall
     */
	* html .ui-autocomplete {
		height: 100px;
	}
</style>