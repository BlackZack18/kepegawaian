<?php
include 'koneksi.php';

// Sesi Login
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Mengambil Page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Query untuk menghitung jumlah pegawai, jabatan, dan departemen
$queryPegawai = "SELECT COUNT(*) as total_pegawai FROM pegawai";
$queryJabatan = "SELECT COUNT(*) as total_jabatan FROM jabatan";
$queryDepartemen = "SELECT COUNT(*) as total_departemen FROM departemen";

$pegawaiResult = mysqli_query($koneksi, $queryPegawai);
$jabatanResult = mysqli_query($koneksi, $queryJabatan);
$departemenResult = mysqli_query($koneksi, $queryDepartemen);

$pegawai = mysqli_fetch_assoc($pegawaiResult);
$jabatan = mysqli_fetch_assoc($jabatanResult);
$departemen = mysqli_fetch_assoc($departemenResult);
?>

<!DOCTYPE html>
<html>

<head>
    <title>UAS PBD || Kepegawaian</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        .mx-auto {
            width: 800px;
        }

        .tampil {
            width: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .card {
            margin-top: 15px;
        }

        .tabel-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            position: relative;
        }

        .table thead {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 2;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .table tbody {
            z-index: 1;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        #content-wrapper {
            min-height: 100vh;
            margin-left: 200px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .footer {
            background: #343a40;
            color: white;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion sticky-top" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Tubes PBD</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <div class="sidebar-heading">
                Menu
            </div>

            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Informasi
            </div>

            <li class="nav-item">
                <a class="nav-link" href="index.php?page=list_informasi_pegawai">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Informasi Pegawai</span>
                </a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Daftar Tabel
            </div>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTabel"
                    aria-expanded="true" aria-controls="collapseTabel">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tabel</span>
                </a>
                <div id="collapseTabel" class="collapse" aria-labelledby="headingTabel" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="index.php?page=list_pegawai">Biodata Pegawai</a>
                        <a class="collapse-item" href="index.php?page=list_jabatan">Daftar Jabatan</a>
                        <a class="collapse-item" href="index.php?page=list_departemen">Daftar Departemen</a>
                        <div class="collapse-divider"></div>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <?php if ($_SESSION['role'] == "admin") { ?>
                <!-- Heading -->
                <div class="sidebar-heading">
                    Pendataan
                </div>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseData"
                        aria-expanded="true" aria-controls="collapseData">
                        <i class="fas fa-fw fa-folder"></i>
                        <span>Data</span>
                    </a>
                    <div id="collapseData" class="collapse" aria-labelledby="headingData" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="index.php?page=informasi_pegawai">Informasi Pegawai</a>
                            <a class="collapse-item" href="index.php?page=pegawai">Pegawai</a>
                            <a class="collapse-item" href="index.php?page=jabatan">Jabatan</a>
                            <a class="collapse-item" href="index.php?page=departemen">Departemen</a>
                            <a class="collapse-item" href="index.php?page=users">User</a>
                            <div class="collapse-divider"></div>
                        </div>
                    </div>
                </li>


                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">
                <?php
            }
            ?>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <!-- Begin Page Content -->
                <div class="container-fluid mt-4">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">PT. Suka Maju</h1>
                        <a href="auth/logout.php" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                            <i class="fas fa-download fa-sm text-white-50"></i>
                            Logout
                        </a>
                    </div>

                    <!-- Simpan disini -->

                    <!-- ^^ Iyaa diantara itu ^^ -->
                    <?php
                    // Menentukan halaman mana yang dimuat
                    if (file_exists("pages/" . $page . ".php")) {
                        include "pages/" . $page . ".php";
                    } else {
                        ?>

                        <div class="container mt-4">
                            <h2 class="mb-4">Dashboard</h2>
                            <div class="row">
                                <!-- Kartu Jumlah Pegawai -->
                                <a href="index.php?page=list_pegawai" class="col-md-4" style="text-decoration: none;">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Pegawai</h5>
                                            <h3><?php echo $pegawai['total_pegawai']; ?></h3>
                                        </div>
                                    </div>
                                </a>

                                <!-- Kartu Jumlah Jabatan -->
                                <a href="index.php?page=list_jabatan" class="col-md-4" style="text-decoration: none;">
                                    <div class="card bg-success text-white">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Jabatan</h5>
                                            <h3><?php echo $jabatan['total_jabatan']; ?></h3>
                                        </div>
                                    </div>
                                </a>

                                <!-- Kartu Jumlah Departemen -->
                                <a href="index.php?page=list_departemen" class="col-md-4" style="text-decoration: none;">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body">
                                            <h5 class="card-title">Total Departemen</h5>
                                            <h3><?php echo $departemen['total_departemen']; ?></h3>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <a href="index.php?page=list_informasi_pegawai" class="btn btn-info mt-5 px-3 py-4 mx-auto" style="text-decoration: none;">  
                                <h5 class="m-0">Informasi Pegawai</h5>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                    <!-- /.container-fluid -->
                </div>
                <!-- End of Main Content -->

            </div>
            <!-- End of Content Wrapper -->

        </div>

        <!-- End of Page Wrapper -->



        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="vendor/chart.js/Chart.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="js/demo/chart-area-demo.js"></script>
        <script src="js/demo/chart-pie-demo.js"></script>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>

</body>

</html>