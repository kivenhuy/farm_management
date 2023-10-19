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

  

  <!-- Include Styles -->
  <!-- $isFront is used to append the front layout styles only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/styles')

  <!-- Include Scripts for customizer, helper, analytics, config -->
  <!-- $isFront is used to append the front layout scriptsIncludes only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/scriptsIncludes')
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
  @include('layouts/sections/scripts')
  @stack('scripts')
</body>

</html>
