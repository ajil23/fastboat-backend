<!doctype html>
<html lang="en">

    
<!-- Mirrored from themesdesign.in/webadmin/layouts/dashboard-sales.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 04 Apr 2024 02:26:17 GMT -->
<head>

        <meta charset="utf-8" />
        <title>Webadmin - Admin & Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesdesign" name="author" />
        <meta name="csrf-token" content="content">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">

        <!-- Bootstrap Css -->
        <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
        {{-- jquery --}}
        <script src="{{asset('assets/js/jquery.min.js')}}"></script>
        {{-- tom select --}}
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <!-- datepicker css -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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

        
   <!-- Static Backdrop Modal -->
   <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Logout confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to logout?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <a class="btn btn-dark" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                  <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                  </form>
              <span>Logout</span></a>
            </div>
        </div>
        </div>
    </div>

        <!-- JAVASCRIPT -->
        <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/libs/metismenujs/metismenujs.min.js')}}"></script>
        <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('assets/libs/eva-icons/eva.min.js')}}"></script>

        <!-- apexcharts -->
        {{-- <script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>
        
        <script src="{{asset('assets/js/pages/dashboard-sales.init.js')}}"></script> --}}

        <script src="{{asset('assets/js/app.js')}}"></script>
        
        <!-- datepicker js -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <!-- include libraries(jQuery, bootstrap) -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        {{-- detail modal --}}
        @yield('script')
        
        {{-- sweetalert --}}
        @include('sweetalert::alert')
        
        
    </body>


<!-- Mirrored from themesdesign.in/webadmin/layouts/dashboard-sales.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 04 Apr 2024 02:26:18 GMT -->
</html>