<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title inertia>{{ config('app.name') }}</title>

  <style>
    body {
      overscroll-behavior-y: none;
    }
  </style>

  <!-- Scripts -->
  @routes
  @viteReactRefresh
  @vite('resources/js/app.jsx')
  @inertiaHead
</head>

<body class="bg-white min-h-screen">
  @inertia
</body>

</html>
