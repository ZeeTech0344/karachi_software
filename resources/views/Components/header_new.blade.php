<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Dashboard 3</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href=" {{ url('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ url('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">



    <link rel="stylesheet" href="{{ url('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{ url('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ url('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Select2 -->








    {{-- 
DELETED --}}

    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ url('plugins/daterangepicker/daterangepicker.css') }}">
    {{-- <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{ url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="{{ url('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
  <!-- Tempusdominus Bootstrap 4 --> --}}
    <link rel="stylesheet" href="{{ url('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    {{-- <!-- Select2 -->
  <link rel="stylesheet" href="{{ url('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="{{ url('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="{{ url('plugins/bs-stepper/css/bs-stepper.min.css') }}">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="{{ url('plugins/dropzone/min/dropzone.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}"> --}}


<style>
    /* .sorting {
        display: 'none' !important;
    } */


    .border-label-container {
    position: relative;
    border-radius: .25rem; /* match Bootstrap's form-control border radius */
  }

  .border-label {
    position: absolute;
    background-color: #fff;
    backdrop-filter: blur (10px);
    top: -1em; /* adjust this value to move the label up and down */
    left: 1rem; /* adjust this value to move the label left and right */
    padding: 0 0.2em;
    color: #495057; /* match Bootstrap's form-control text color */
  }

  .border-input {
    border: 0;
    outline: 0;
    box-shadow: none;
  }

  /* Apply the same height as Bootstrap's form-control to match alignment */
  .border-input {
    height: calc(1.5em + 0.75rem + 2px);
  }

  
</style>

</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white" style="background-color:#17a2b8;">
            <div class="container">
                <span class="brand-text font-weight-light"></span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse"
                    data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>



                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="{{ url('/') }}" class="nav-link" style="color:white;"><i
                                    class="fas fa-truck"></i> Supplier&nbsp;Information</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('/transaction-form') }}" class="nav-link" id="add-supplier-expense-record" style="color:white;"><i
                                    class="fas fa-exchange-alt"></i>
                                Supplier/Transaction</a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link" style="color:white;" id="ledger"><i
                                    class="fas fa-file-invoice"></i>
                                Ledger</a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{url('logout')}}" class="nav-link" style="color:white;" id="ledger">   <i class="logout-icon fas fa-sign-out-alt"></i>Logout</a>
                        </li>
                        
                        <!-- <li class="nav-item">-->
                        <!--    <a href="{{url('register')}}" class="nav-link" style="color:white;" id="ledger">   <i class="register-icon fas fa-user-plus"></i>Register</a>-->
                        <!--</li>-->

                        <li>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle text-white" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 Cousting
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                  <a class="dropdown-item" href="{{'create-items-form'}}">Create Items</a>
                                  <a class="dropdown-item" href="{{'item-rate-form'}}">Create Bill</a>
                                  <a class="dropdown-item" href="#" id="get_cousting_report_view">Report</a>
                                  <a class="dropdown-item" href="{{'rate-list-filter'}}">Rate List</a>
                                </div>
                              </div>
                        </li>
                        
                    </ul>


                    


                
                    <form class="form-inline mr-2 ">
                        <div class="input-group input-group-md">

                            <select name="search_type" id="search_type" class="form-control" onchange="getSupplierDataHeader()">
                                <option value="">Select Type</option>
                                <option>Supplier</option>
                                {{-- <option>Expense</option> --}}
                            </select>

                        </div>
                    </form>

                    <form class="form-inline d-none" id="search_supplier_name_div">

                        <select class="form-control select2" name="search_supplier_name" id="search_supplier_name" onchange="getSupplierDataHeader()" >
                            <option value="">Select Supplier</option>

                        </select>

                    </form>

                    <form class="form-inline d-none mr-2" id="search_expense_div">
                        <div class="input-group input-group-md">
                            <input class="form-control form-control-navbar" type="search"  name="search_expense" id="search_expense" 
                                placeholder="Search Expense" aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-navbar">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>


                    <form class="form-inline ml-2">
                        <div class="input-group input-group-md">

                            <select name="select_duration" id="select_duration" onchange="getSupplierDataHeader()" class="form-control">
                                <option value="">Select Duration</option>
                                <option value="Today">Today</option>
                                <option value="Yesterday">Yesterday</option>
                                <option value="Last 7 Days">Last 7 Days</option>
                                <option value="first day of current month">Current Month</option>
                                <option value="first day of last month">Last Month</option>
                                
                            </select>

                        </div>
                    </form>

                

                </div>


            </div>

    </div>
    </nav>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper p-2">
        <!-- Content Header (Page header) -->

        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div>
