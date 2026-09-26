<?php include 'base.php';?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title>KPI Academic Performance Monitoring System</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="css/owl.carousel.min.css">
<link rel="stylesheet" href="css/animate.css">
<link rel="stylesheet" href="css/hover-min.css">
<link rel="stylesheet" type="text/css" href="css/slide-social-buttons.css">

<link href="css/style.css" rel="stylesheet">

<style>
    body {
        font-size: 16px;
    }
    td, th {
        text-align: center;
    }
    th {
        color: #0099CC;
        font-weight: bold;
    }
    .btn {
        font-size: 16px;
    }
</style>

<style media="print">
    /* إخفاء العناصر غير المرغوب طباعتها */
    .navbar, 
    .header, 
    .navbarmun, 
    .abuat, 
    .scroll-top-wrapper,
    .btn-print,
    .btn-primary,
    .login-panel .panel-heading a,
    form,
    button,
    .fa-print {
        display: none !important;
    }
    
    /* تحسين مظهر الجداول عند الطباعة */
    body {
        padding: 0;
        margin: 0;
        font-size: 14px;
    }
    
    .table {
        border: 1px solid #ddd;
        width: 100%;
        border-collapse: collapse;
    }
    
    .table th, .table td {
        border: 1px solid #ddd;
        padding: 5px;
    }
    
    .panel {
        border: 1px solid #ddd;
        margin-bottom: 10px;
    }
    
    .panel-heading {
        background-color: #f5f5f5 !important;
        font-weight: bold;
        padding: 8px;
    }
    
    a {
        text-decoration: none;
        color: black;
    }
    
    @page {
        size: A4 landscape;
        margin: 1cm;
    }
</style>

</head>
<body>
    <!--header-->
    <div class="header">
        <div class="overlay">
            <div class="container">
                <div class="row verticals">
                    <div class="col-md-6 text-center">
                        <h1 style="color: #fff; font-weight: bold;">KPI Academic Performance Monitoring System</h1>
                    </div>
                    <div class="col-md-3 col-md-offset-3 text-center">
                        <div class="logo2">
                            <br/>
                            <br/>
                            <img class="img-responsive center-block" src="img/logo.png">
                            <br/>
                            <br/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--header-->

    <!--navbar-->
    <div class="navbarmun">
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header text-center">
                    <button type="button" class="navbar-toggle collapsed"
                        data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
                        aria-expanded="false">
                        <span>Main Menu</span>
                    </button>
                </div>
                <div class="collapse navbar-collapse"
                    id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.php"><i class="fa-solid fa-home"></i></a></li>
                            
                        <?php if ($_SESSION['user_id'] == "") { ?>
                            <li><a href="login.php">Login</a></li>
                        <?php } ?>
                        
                        <?php if ($_SESSION['user_type'] == 'super_admin') { ?>
                            <li><a href="admin_show_users.php">Users</a></li>
                            <li><a href="admin_show_colleges.php">Colleges</a></li>
                            <li><a href="admin_show_departments.php">Departments</a></li>
                            <li><a href="admin_show_programs.php">Programs</a></li>
                            <li><a href="admin_show_kpis.php">KPIs</a></li>
                            <li><a href="admin_profile.php">Profile</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        <?php } ?>

                        <?php if ($_SESSION['user_type'] == 'qa_director') { ?>
                            <li><a href="qa_director_show_all.php">All Results</a></li>
                            <li><a href="qa_director_profile.php">Profile</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        <?php } ?>

                        <?php if ($_SESSION['user_type'] == 'college_dean') { ?>
                            <li><a href="dean_show_kpis.php">College KPIs</a></li>
                            <li><a href="dean_profile.php">Profile</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        <?php } ?>

                        <?php if ($_SESSION['user_type'] == 'program_head') { ?>
                            <li><a href="program_head_show_kpis.php">Program KPIs</a></li>
                            <li><a href="program_head_profile.php">Profile</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        <?php } ?>

                        <?php if ($_SESSION['user_type'] == 'faculty') { ?>
                            <li><a href="faculty_show_kpis.php">My KPIs</a></li>
                            <li><a href="faculty_profile.php">Profile</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        <?php } ?>

                        <?php if ($_SESSION['user_type'] == 'auditor') { ?>
                            <li><a href="auditor_view_dashboard.php">Dashboard</a></li>
                            <li><a href="auditor_profile.php">Profile</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        <?php } ?>

                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <!--navbar-->

    <!--content-->
    <div class="content">
        <div class="">
            <div class="our-Services">
                <div class="container">