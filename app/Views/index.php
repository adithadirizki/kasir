<?= $this->extend('template/template'); ?>

<?= $this->section('header') ?>

<link rel="stylesheet" href="<?= base_url('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
</link>

<?= $this->endSection() ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $jum_kategori; ?></h3>
                <p>Kategori</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $jum_produk; ?></h3>
                <p>Produk</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $jum_produk_terjual; ?></h3>
                <p>Produk Terjual</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $jum_pesanan; ?></h3>
                <p>Total Pesanan</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col">
        <div class="teble-responsive bg-white p-3">
        <table id="tbl_pesanan_baru" class="table">
            <thead>
               <tr>
                  <th>#</th>
                  <th>Faktur</th>
                  <th>Produk</th>
                  <th>Total Item</th>
                  <th>Total Bayar</th>
                  <th>Tanggal</th>
                  <th>Aksi</th>
               </tr>
            </thead>
        </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('footer') ?>

<script src="<?= base_url('template/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script>
    $(document).ready(function() {
      var tbl_pesanan_baru = $('#tbl_pesanan_baru').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         scrollY: true,
         order: [
            [1, 'asc']
         ],
         ajax: {
            url: "<?= base_url('pesananbaru') ?>",
            type: "post"
         },
         columns: [{
               "data": null,
               "mRender": function(data, row, type, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
               },
               "orderable": false
            },
            {
               "data": "faktur"
            },
            {
               "data": "produk"
            },
            {
               "data": "item"
            },
            {
               "data": "total_bayar"
            },
            {
               "data": "created_at"
            },
            {
               "data": "faktur",
               "mRender": function(faktur) {
                  // return '<button class="btn btn-xs btn-primary text-bold"><i class="fa fa-edit mr-1"></i>DETAIL</button>';
                  return '<a href="<?= base_url('struk') ?>/' + faktur + '" class="btn btn-xs btn-primary text-bold mr-1"><i class="fa fa-edit mr-1"></i>DETAIL</a>';
               },
               "orderable": false
            }
         ]
      })
    })
</script>

<?= $this->endSection() ?>