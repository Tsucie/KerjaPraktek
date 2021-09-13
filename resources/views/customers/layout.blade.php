<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="route" content="{{ __('http://127.0.0.1:8000') }}">
	<title>{{ $title ?? 'Silungkang Venue' }}</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
	<link rel="stylesheet" href="{{ asset('assets') }}/css/plugins.min.css" />
	<link rel="stylesheet" href="{{ asset('assets') }}/css/bootstrap.min.css" />
	<link rel="stylesheet" href="{{ asset('assets') }}/css/exclude.css" />
	<link rel="stylesheet" href="{{ asset('assets') }}/css/style.css" />
  <link rel="stylesheet" href="{{ asset('assets') }}/css/responsive.css" />
  <link type="text/css" href="{{ asset('assets') }}/vendor/notifIt/css/notifIt.css" rel="stylesheet">
</head>
<body>
  <main>
    {{-- Include customer page main header --}}
		@include('layouts.headers.csmainheader')
    {{-- Load main section content --}}
    @yield('content')
    {{-- Include customer page footer --}}
		@include('layouts.footers.csmainfooter')
  </main>
  {{-- Include customer page javascripts --}}
  @include('csjavascript')
</body>
</html>