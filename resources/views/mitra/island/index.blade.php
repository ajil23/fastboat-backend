@extends('admin.admin_master') 
@section('admin')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center mb-2">
                                <h5 class="card-title">Products Of The Month</h5>
                                <div class="ms-auto">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle text-reset" href="#" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="text-muted font-size-12">Sort By: </span> <span class="fw-medium"> Monthly<i class="mdi mdi-chevron-down ms-1"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                            <a class="dropdown-item" href="#">Weekly</a>
                                            <a class="dropdown-item" href="#">Monthly</a>
                                            <a class="dropdown-item" href="#">Yearly</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
                                    <thead>
                                        <tr>
                                            <th style="width: 90px;">
                                               Product
                                            </th>
                                            <th  style="width: 210px;">Product Name</th>
                                            <th>Customer Name</th>
                                            <th>Order ID</th>
                                            <th>Color</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th style="width: 270px;">Trend</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="avatar">
                                                    <div class="product-img avatar-title img-thumbnail bg-primary-subtle  border-0">
                                                        <img src="assets/images/product/img-1.png" class="img-fluid" alt="">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-semibold">Office Chair Crime</td>
                                            <td>
                                                Neal Matthews
                                            </td>
                                            <td>
                                            #526552
                                            </td>
                                            <td>
                                                Gray
                                            </td>
                                            <td>12/01/2022</td>
                                            <td><span class="badge bg-primary-subtle text-primary  font-size-12">Pending</span></td>
                                            <td>
                                                <div id="chart-sparkline1" data-colors='["#1f58c7"]'></div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Print</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>
                                                <div class="avatar">
                                                    <div class="product-img avatar-title img-thumbnail bg-success-subtle  border-0">
                                                        <img src="assets/images/product/img-2.png" class="img-fluid" alt="">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-semibold">Sofa Home Chair Black</td>
                                            <td>
                                                Connie Franco
                                            </td>
                                            <td>
                                            #746648
                                            </td>
                                            <td>
                                                Black
                                            </td>
                                            <td>14/01/2022</td>
                                            <td><span class="badge bg-success-subtle text-success  font-size-12">Active</span></td>
                                            <td>
                                                <div id="chart-sparkline2" data-colors='["#1f58c7"]'></div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Print</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="avatar">
                                                    <div class="product-img avatar-title img-thumbnail bg-danger-subtle  border-0">
                                                        <img src="{{asset('assets/images/product/img-3.png')}}" class="img-fluid" alt="">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-semibold">Tuition Classes Chair</td>
                                            <td>
                                                Paul Reynolds
                                            </td>
                                            <td>
                                            #125635
                                            </td>
                                            <td>
                                                Crime
                                            </td>
                                            <td>17/01/2022</td>
                                            <td><span class="badge bg-success-subtle text-success  font-size-12">Active</span></td>
                                            <td>
                                                <div id="chart-sparkline3" data-colors='["#1f58c7"]'></div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Print</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="avatar">
                                                    <div class="product-img avatar-title img-thumbnail bg-primary-subtle  border-0">
                                                        <img src="{{asset('assets/images/product/img-4.png')}}" class="img-fluid" alt="">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-semibold">Dining Table Chair</td>
                                            <td>
                                                Ronald Patterson
                                            </td>
                                            <td>
                                            #236521
                                            </td>
                                            <td>
                                                Crime
                                            </td>
                                            <td>18/01/2022</td>
                                            <td><span class="badge bg-primary-subtle text-primary  font-size-12">Pending</span></td>
                                            <td>
                                                <div id="chart-sparkline4" data-colors='["#1f58c7"]'></div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Print</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="avatar">
                                                    <div class="product-img avatar-title img-thumbnail bg-success-subtle  border-0">
                                                        <img src="{{asset('assets/images/product/img-5.png')}}" class="img-fluid" alt="">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-semibold">Home & Office Chair</td>
                                            <td>
                                                Adella Perez
                                            </td>
                                            <td>
                                            #236521
                                            </td>
                                            <td>
                                                Crime
                                            </td>
                                            <td>18/01/2022</td>
                                            <td><span class="badge bg-primary-subtle text-primary  font-size-12">Pending</span></td>
                                            <td>
                                                <div id="chart-sparkline5" data-colors='["#1f58c7"]'></div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Print</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->

        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>document.write(new Date().getFullYear())</script> © webadmin.
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Crafted with <i class="mdi mdi-heart text-danger"></i> by <a href="https://themesdesign.com/" target="_blank" class="text-reset">Themesdesign</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>