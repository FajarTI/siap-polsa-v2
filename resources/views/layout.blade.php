<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Admin')</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ config('app.url') }}/skydash/dist/assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="{{ config('app.url') }}/skydash/dist/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="{{ config('app.url') }}/skydash/dist/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet"
        href="{{ config('app.url') }}/skydash/dist/assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet"
        href="{{ config('app.url') }}/skydash/dist/assets/vendors/mdi/css/materialdesignicons.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ config('app.url') }}/skydash/dist/assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ config('app.url') }}/skydash/dist/assets/images/favicon.png" />
    {{-- @vite(['resources/js/app.js']) --}}
     @livewireStyles

</head>

<body>
    <div class="container-scroller">
        <!-- partial:/skydash/dist/partials/_navbar.html -->
        @include('partials.navbar')
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial/skydash/dist/partials/_sidebar.html -->
            @include('partials.sidebar')
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content', 'Content Here...')
                </div>
                <!-- content-wrapper ends -->
                <!-- partial/skydash/dist/partials/_footer.html -->
                @include('partials.footer')
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ config('app.url') }}/skydash/dist/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ config('app.url') }}/skydash/dist/assets/js/off-canvas.js"></script>
    <script src="{{ config('app.url') }}/skydash/dist/assets/js/template.js"></script>
    <script src="{{ config('app.url') }}/skydash/dist/assets/js/settings.js"></script>
    <script src="{{ config('app.url') }}/skydash/dist/assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <!-- End custom js for this page-->
    @livewireScripts
</body>

</html>
