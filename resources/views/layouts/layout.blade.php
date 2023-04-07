<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GrabNGo- Dashboard</title>

    <!-- Custom fonts for this template-->
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('public/site/css/fontawesome-free/css/all.min.css') }}" type="text/css">

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="{{ asset('public/site/css/bala.DualSelectList.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/site/css/sb-admin-2.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/site/css/toastr.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/site/css/custom.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/site/js/datatables/dataTables.bootstrap4.min.css') }}" type="text/css">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @php
        $currentUrlExplod = explode("/", $_SERVER['REQUEST_URI']);

        $currentUrl = isset($currentUrlExplod[1]) ? $currentUrlExplod[1] : '';
        
        @endphp

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('dashboard')}}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Grab N Go</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ $currentUrl == 'dashboard' ? 'active' : '' }}">
                <a class="nav-link" href="{{route('dashboard')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Pages Collapse Menu -->
                <li class="nav-item {{ $currentUrl == 'mastercategory' || $currentUrl == 'maincategory' || $currentUrl == 'category' || $currentUrl == 'subcategory'  ? 'active' : '' }}">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fa fa-list" aria-hidden="true"></i>
                    <span>All Categories</span>
                </a>
                <div id="collapseTwo" class="collapse {{ $currentUrl == 'mastercategory' || $currentUrl == 'maincategory' || $currentUrl == 'category' || $currentUrl == 'subcategory'  ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">All Categories</h6>
                        <a class="collapse-item {{ $currentUrl == 'mastercategory' ? 'active' : '' }}" href="{{ route('mastercategory.list') }}">Master Category</a>
                        <a class="collapse-item {{ $currentUrl == 'maincategory' ? 'active' : '' }}" href="{{ route('maincategory.list') }}">Main Category</a>
                        <a class="collapse-item {{ $currentUrl == 'category' ? 'active' : '' }}" href="{{ route('category.list') }}">Category</a>
                        <a class="collapse-item {{ $currentUrl == 'subcategory' ? 'active' : '' }}" href="{{ route('subcategory.list') }}">Sub Category</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">
            <li class="nav-item {{ $currentUrl == 'user' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.list') }}">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span>Users</span></a>
                </li>
                <hr class="sidebar-divider">
                <li class="nav-item {{ $currentUrl == 'banner' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('banner.list') }}">
                        <i class="fas fa-image"></i>
                        <span>Banner</span></a>
                    </li>
                    <hr class="sidebar-divider">
                    <li class="nav-item {{ $currentUrl == 'bannercategory' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('bannercategory.list') }}"> 
                            <i class="fa fa-file-image" aria-hidden="true"></i>
                            <!-- <i class="fas fa-image"></i>  -->
                            <span>Banner Category Items</span></a>
                        </li>
                        <hr class="sidebar-divider">

                        <li class="nav-item {{ $currentUrl == 'explore' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('explore.list') }}"> 
                                <i class="fa fa-list-alt" aria-hidden="true"></i>
                                <!-- <i class="fas fa-image"></i>  -->
                                <span>Explore Items</span></a>
                            </li>
                            <hr class="sidebar-divider">

                            <li class="nav-item {{ $currentUrl == 'offerproduct' ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('offerproduct.list') }}">
                                    <i class="fa fa-tag fa-lg"></i>
                                    <span>Offer Product</span></a>
                                </li>
                                
                                <hr class="sidebar-divider">
                                <li class="nav-item {{ $currentUrl == 'product' ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('product.list') }}">
                                        <i class="fa fa-archive" aria-hidden="true"></i>
                                        <span>Product</span></a>
                                    </li>
                                    

                                    <div class="text-center d-none d-md-inline">
                                        <button class="rounded-circle border-0" id="sidebarToggle"></button>
                                    </div>

                                    
                                </ul>

                                <div id="content-wrapper" class="d-flex flex-column">

                                    <div id="content">

                                        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                                            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                                                <i class="fa fa-bars"></i>
                                            </button>

                                            <form  class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                                                <div class="input-group">
                                                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                                    aria-label="Search" aria-describedby="basic-addon2">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" type="button">
                                                            <i class="fas fa-search fa-sm"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>

                                            <ul class="navbar-nav ml-auto">

                                                <li class="nav-item dropdown no-arrow d-sm-none">
                                                    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-search fa-fw"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                                aria-labelledby="searchDropdown">
                                                <form class="form-inline mr-auto w-100 navbar-search">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control bg-light border-0 small"
                                                        placeholder="Search for..." aria-label="Search"
                                                        aria-describedby="basic-addon2">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary" type="button">
                                                                <i class="fas fa-search fa-sm"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </li>


                                        <div class="topbar-divider d-none d-sm-block"></div>

                                        <!-- Nav Item - User Information -->
                                        <li class="nav-item dropdown no-arrow">
                                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400" aria-hidden="true"></i>
                                <!-- <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg"> -->
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <!-- <div class="container-fluid"> -->
                    @yield('content')

                    <!-- </div> -->
                    <!-- /.container-fluid -->
                </div>         
                @extends('layouts.footer')

                <!--NOTE: wrapper id and content-wrapper id div close in footer blade file for set UI. -->



            </body>

            </html>