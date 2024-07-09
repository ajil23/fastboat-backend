<!doctype html>
<html lang="en">

    
<!-- Mirrored from themesdesign.in/webadmin/layouts/dashboard-sales.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 04 Apr 2024 02:26:17 GMT -->
<head>

        <meta charset="utf-8" />
        <title>Sales | webadmin - Admin & Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesdesign" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">

        <!-- Bootstrap Css -->
        <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

    </head>

    
    <body>

    <!-- <body data-layout="horizontal"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">
          @include('admin.components.header')
            
            <!-- ========== Left Sidebar Start ========== -->
            @include('admin.components.sidebar')
            

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            @yield('admin')
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->


        <!-- chat offcanvas -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasActivity" aria-labelledby="offcanvasActivityLabel">
            <div class="offcanvas-header border-bottom">
              <h5 id="offcanvasActivityLabel">Offcanvas right</h5>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              ...
            </div>
        </div>

        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenujs/metismenujs.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/eva-icons/eva.min.js')}}"></script>

         <!-- apexcharts -->
         <script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>
        
        <script src="{{asset('assets/js/pages/dashboard-sales.init.js')}}"></script>

        <script src="{{asset('assets/js/app.js')}}"></script>

    </body>


<!-- Mirrored from themesdesign.in/webadmin/layouts/dashboard-sales.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 04 Apr 2024 02:26:18 GMT -->
</html>