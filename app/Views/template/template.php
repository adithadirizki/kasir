<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $title ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('template/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?= base_url('template/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
  <!-- Sweet Alert -->
  <link rel="stylesheet" href="<?= base_url('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">
  <?= $this->renderSection('header') ?>
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('template/dist/css/adminlte.min.css') ?>">
  <!-- Google Font: Source Sans Pro -->
  <!-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fa fa-lg fa-cog"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a href="<?= base_url('logout') ?>" class="dropdown-item">
              <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <?php
    $pesanan_nav = ["Buat Pesanan", "Riwayat Pesanan", "Struk Detail"];
    $produk_nav = ["Produk", "Detail Produk", "Tambah Produk"];
    $kategori_nav = ["Kategori", "Detail Kategori"];
    $barang_nav = ["Barang"];
    $laporan_nav = ["Laporan Pesanan", "Laporan Barang Masuk", "Laporan Barang Keluar"];
    $l_pesanan = ["Laporan Pesanan"];
    $l_barang_masuk = ["Laporan Barang Masuk"];
    $l_barang_keluar = ["Laporan Barang Keluar"];
    ?>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-light-navy elevation-4">

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?= base_url('template/dist/img/user2-160x160.jpg') ?>" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block"><?= $dataSession['nama']; ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
              <a href="<?= base_url(); ?>" class="nav-link <?= $title == 'Dashboard' ? 'active' : '' ?>">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('produk'); ?>" class="nav-link <?= (in_array($title, $produk_nav)) ? 'active' : '' ?>">
                <i class="nav-icon fas fa-cube"></i>
                <p>
                  Produk
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('kategori'); ?>" class="nav-link <?= (in_array($title, $kategori_nav)) ? 'active' : '' ?>">
                <i class="nav-icon fas fa-bookmark"></i>
                <p>
                  Kategori
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('riwayatpesanan'); ?>" class="nav-link <?= (in_array($title, $pesanan_nav)) ? 'active' : '' ?>">
                <i class="nav-icon fas fa-clipboard-list"></i>
                <p>
                  Pesanan
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('barang'); ?>" class="nav-link <?= (in_array($title, $barang_nav)) ? 'active' : '' ?>">
                <i class="nav-icon fas fa-box-open"></i>
                <p>
                  Barang
                </p>
              </a>
            </li>
            <li class="nav-item has-treeview <?= (in_array($title, $laporan_nav)) ? 'menu-open' : '' ?>">
              <a href="#" class="nav-link <?= (in_array($title, $laporan_nav)) ? 'active' : '' ?>">
                <i class="nav-icon fas fa-clipboard-list"></i>
                <p>
                  Laporan
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= base_url('laporan/pesanan'); ?>" class="nav-link <?= (in_array($title, $l_pesanan)) ? 'active' : '' ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Pesanan</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('laporan/barangmasuk'); ?>" class="nav-link <?= (in_array($title, $l_barang_masuk)) ? 'active' : '' ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Barang Masuk</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('laporan/barangkeluar'); ?>" class="nav-link <?= (in_array($title, $l_barang_keluar)) ? 'active' : '' ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Barang Keluar</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('logout'); ?>" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                  Logout
                </p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"><?= $title ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><?= $title ?></li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">

          <?= $this->renderSection('content') ?>

        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- <footer class="main-footer">
      <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong>
      All rights reserved.
      <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.0.5
      </div>
    </footer> -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="<?= base_url('template/plugins/jquery/jquery.min.js') ?>"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url('template/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <!-- overlayScrollbars -->
  <script src="<?= base_url('template/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
  <!-- Sweet Alert -->
  <script src="<?= base_url('template/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

  <?= $this->renderSection('footer') ?>

  <!-- AdminLTE App -->
  <script src="<?= base_url('template/dist/js/adminlte.js') ?>"></script>

</body>

</html>