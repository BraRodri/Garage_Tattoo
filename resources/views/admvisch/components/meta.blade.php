<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="" />
<meta name="author" content="" />
@yield('meta')

<base href="
@php
    Config::get('BASE_URL', 'default');
@endphp
 "
/>
<link rel="icon" href={{asset('images/favicon.jpg')}}>

<title>
    @php
    echo APP_NAME;
@endphp
    |
    @php
    echo APP_SLOGAN;
@endphp
    </title>
