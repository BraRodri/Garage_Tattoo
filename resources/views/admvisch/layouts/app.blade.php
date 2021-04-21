
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
	<x-metaa/>
	<x-csss/>
</head>
<body class="page-body" data-url="http://neon.dev">

<div class="page-container sidebar-collapsedx @php if(session()->get('sidebar_collapsed')===true){ echo 'sidebar-collapsed';} @endphp">
	
	<x-sidebar-menu/>

	<div class="main-content">
				
		<x-header-menu/>
		
		<hr />

	
        {{$slot}}

		
        <x-footer-menu/>
		
	</div>
	
</div>




<script src={{ asset('assets/js/autocomplete/jquery-ui.1.11.3.js') }}></script>
<x-jss>
	{{$js}}
</x-jss>

</body>
</html>