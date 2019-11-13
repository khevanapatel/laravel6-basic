<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> Dashboard </title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

   @include('layouts.admin.head')
   @yield('css')
</head>
<body>
	<div id="app" class="page-body-wrapper">
	    <div class="page-sidebar">
	    	@include('layouts.admin.sidebar')
	    </div>
	    <div class="page-body">
		    @include('layouts.admin.header')
		    <div class="container">
		        <main class="py-4">
		            @yield('content')
		        </main>
	    	</div>
	    </div>
	    @include('layouts.admin.script')
	    @yield('script')
	</div>
</body>
</html>
