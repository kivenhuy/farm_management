@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

@php
    $configData = Helper::appClasses();
@endphp

<!DOCTYPE html>
<html lang="{{ session()->get('locale') ?? app()->getLocale() }}" class="light-style layout-navbar-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{url('/')}}" data-framework="laravel" data-template="vertical-menu-theme-default-light">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>@yield('title', 'upstream')</title>
  <!-- laravel CRUD token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Canonical SEO -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/aiz-core.css') }}" />
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


  <!-- Include Styles -->
  <!-- $isFront is used to append the front layout styles only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/styles')

  <!-- Include Scripts for customizer, helper, analytics, config -->
  <!-- $isFront is used to append the front layout scriptsIncludes only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/scriptsIncludes')

  <link rel="stylesheet" href="{{ asset('custom\css\jquery.datetimepicker.min.css')}}">
  <link rel="stylesheet" href="{{ asset('custom\css\style.css?v=')}}{{ now()->timestamp}}">

  @yield('style')

</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
      
          @include('layouts/app-sections/verticalMenu')
      
      
          <!-- Layout page -->
          <div class="layout-page">
      
            {{-- Below commented code read by artisan command while installing jetstream. !! Do not remove if you want to use jetstream. --}}
            {{-- <x-banner /> --}}
      
            @include('layouts/app-sections/navbar')
      
      
            <!-- Content wrapper -->
            <div class="content-wrapper">
      
              <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
      
                  @yield('content')
      
                </div>
                <!-- / Content -->
      
                <!-- Footer -->
                    {{-- @include('layouts/sections/footer/footer') --}}
                <!-- / Footer -->
                <div class="content-backdrop fade"></div>
              </div>
              <!--/ Content wrapper -->
            </div>
            <!-- / Layout page -->
          </div>
      
          <!-- Drag Target Area To SlideIn Menu On Small Screens -->
          <div class="drag-target"></div>
        </div>
        <!-- / Layout wrapper -->

  

  <!-- Include Scripts -->
  <!-- $isFront is used to append the front layout scripts only on the front layout otherwise the variable will be blank -->
  {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
  {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}" ></script>
	<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}" ></script>
	<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}" ></script>
	<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}" ></script>
	<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}" ></script>
   --}}
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
  
  @include('layouts/sections/scripts')
  @stack('scripts')


  {{-- <script src="{{ asset('js/aiz-core.js') }}" ></script> --}}
</body>

</html>


