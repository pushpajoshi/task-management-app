<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

  <!-- Styles -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

  <!-- jQuery + DataTables JS -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <!-- jQuery -->




</head>

<body class="font-sans antialiased">
  <div class="min-h-screen bg-gray-100">
    @include('layouts.navigation')
  @if(session('success'))
    <div class="flex justify-end mb-4">
      <div id="toast-success"
          class="fixed top-5 right-5 bg-green-200 text-green-800 px-4 py-2 rounded shadow-lg opacity-0 transform translate-x-20 transition-all duration-500">
          {{ session('success') }}
      </div>
    </div>
    <script>
      const toast = document.getElementById('toast-success');
      toast.classList.remove('opacity-0', 'translate-x-20');
      toast.classList.add('opacity-100', 'translate-x-0');
      setTimeout(() => {
          toast.classList.remove('opacity-100');
          toast.classList.add('opacity-0', 'translate-x-20');
      }, 3000);
    </script>
    @endif

    @if(session('error'))
    <div class="flex justify-end mb-4">
      <div id="toast-error"
          class="fixed top-5 right-5 bg-red-200 text-red-800 px-4 py-2 rounded shadow-lg opacity-0 transform translate-x-20 transition-all duration-500">
          {{ session('error') }}
      </div>
    </div>
    <script>
      const toast = document.getElementById('toast-error');
      toast.classList.remove('opacity-0', 'translate-x-20');
      toast.classList.add('opacity-100', 'translate-x-0');
      setTimeout(() => {
          toast.classList.remove('opacity-100');
          toast.classList.add('opacity-0', 'translate-x-20');
      }, 3000);
    </script>
    @endif

    <!-- Page Heading -->
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{ $header }}
      </div>
    </header>


    <!-- Page Content -->
    <main>
      {{ $slot }}
    </main>


    <!-- ✅ Footer -->
    <footer class="bg-white shadow mt-auto">
      <div class="max-w-7xl mx-auto py-4 px-6 text-center text-gray-600">
        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
      </div>
    </footer>


  </div>
</body>

</html>