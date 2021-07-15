<?= $this->extend('template/template') ?>

<?= $this->section('header') ?>

<link rel="stylesheet" href="<?= base_url('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
</link>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
   <div class="col-lg-3 col-6">
      <div class="small-box bg-info">
         <div class="inner">
            <h3><?= $simply['pesanan_hari_ini'] ?></h3>
            <p>Pesanan Hari Ini</p>
         </div>
         <div class="icon">
            <i class="fa fa-clipboard-list"></i>
         </div>
         <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
   </div>
   <div class="col-lg-3 col-6">
      <div class="small-box bg-primary">
         <div class="inner">
            <h3><?= $simply['pesanan_bulan_ini'] ?></h3>
            <p>Pesanan Bulan Ini</p>
         </div>
         <div class="icon">
            <i class="fa fa-cart-arrow-down"></i>
         </div>
         <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
   </div>
   <div class="col-lg-3 col-6">
      <div class="small-box bg-warning">
         <div class="inner">
            <h3><?= $simply['pendapatan_hari_ini'] ?></h3>
            <p>Pendapatan Hari Ini</p>
         </div>
         <div class="icon">
            <i class="fa fa-money-bill-alt"></i>
         </div>
         <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
   </div>
   <div class="col-lg-3 col-6">
      <div class="small-box bg-success">
         <div class="inner">
            <h3><?= $simply['total_pendapatan'] ?></h3>
            <p>Total Pendapatan</p>
         </div>
         <div class="icon">
            <i class="fa fa-money-check-alt"></i>
         </div>
         <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
   </div>
</div>
<div class="card">
   <div class="card-body">
      <div class="table-responsive">
         <div class="form-group">
            <label for="range_date">Filter</label>
            <select name="range" id="range_date" class="form-control w-auto">
               <option value="">Semua</option>
               <option value="hari_ini">Hari ini</option>
               <option value="kemarin">Kemarin</option>
               <option value="bulan_ini">Bulan ini</option>
               <option value="tahun_ini">Tahun ini</option>
               <option value="tahun_kemarin">Tahun Kemarin</option>
            </select>
         </div>
         <table id="riwayatpesanan" class="table">
            <thead>
               <tr>
                  <th>#</th>
                  <th>Faktur</th>
                  <th>Produk</th>
                  <th>Total Item</th>
                  <th>Pendapatan</th>
                  <th>Tanggal</th>
                  <th>Aksi</th>
               </tr>
            </thead>
            <tfoot>
               <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('footer') ?>

<script src="<?= base_url('template/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('template/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('template/plugins/jszip/jszip.min.js') ?>"></script>
<script src="<?= base_url('template/plugins/pdfmake/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('template/plugins/pdfmake/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('template/plugins/datatables-buttons/js/buttons.html5.js') ?>"></script>
<script src="<?= base_url('template/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('template/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>
<script>
   $(document).ready(function() {
      var tbl_riwayat_pesanan = $('#riwayatpesanan').DataTable({
         processing: true,
         serverSide: true,
         responsive: true,
         scrollY: true,
         dom: 'Bfrltip',
         buttons: [{
               extend: "copyHtml5",
               exportOptions: {
                  columns: [0, ':visible']
               },
               className: "btn btn-primary text-bold",
               footer: true
            },
            {
               extend: "excelHtml5",
               exportOptions: {
                  columns: ':visible'
               },
               className: "btn btn-primary text-bold",
               footer: true
            },
            {
               extend: "pdfHtml5",
               exportOptions: {
                  columns: ':visible'
               },
               className: "btn btn-primary text-bold",
               footer: true
            },
            {
               extend: "print",
               exportOptions: {
                  columns: [0, 1, 2, 3, 4, 5]
               },
               className: "btn btn-primary text-bold",
               footer: true
            },
            {
               extend: "colvis",
               className: "btn btn-secondary text-bold"
            }
         ],
         order: [
            [5, 'desc']
         ],
         ajax: {
            url: "<?= base_url('riwayatpesanan') ?>",
            type: "post",
            data: function(data) {
               data.searchByDate = $('#range_date').val()
            }
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
                  return '<a href="<?= base_url('struk') ?>/' + faktur + '" class="btn btn-xs btn-primary text-bold mr-1"><i class="fa fa-edit mr-1"></i>DETAIL</a><button type="button" class="btn btn-xs btn-danger text-bold hapus-pesanan" data-id="' + faktur + '"><i class="fa fa-trash-alt mr-1"></i>HAPUS</button>';
               },
               "orderable": false
            }
         ],
         footerCallback: function(tfoot, row, start, end, display) {
            var res = this.api().ajax.json();
            if (res) {
               var th = $(tfoot).find('th');
               th.eq(4).html('Total: ' + res['total_pendapatan'])
            }
         }
      })
      $('#range_date').change(function() {
         tbl_riwayat_pesanan.draw()
      })
      $(document).on('click', '.hapus-pesanan', function() {
         Swal.fire({
            title: "Peringatan",
            text: "Yakin ingin menghapus ?",
            icon: "warning",
            showCancelButton: true
         }).then((result) => {
            if (result.value) {
               $.ajax({
                  url: "<?= base_url('hapuspesanan') ?>/" + $(this).data('id'),
                  type: "delete",
                  dataType: "json",
                  beforeSend: function() {
                     // 
                  },
                  success: function(hasil) {
                     if (hasil.error == false) {
                        Swal.fire({
                           title: "Berhasil",
                           text: hasil.msg,
                           icon: "success",
                           showConfirmButton: false,
                           timer: 2000
                        }).then(function() {
                           tbl_riwayat_pesanan.ajax.reload()
                        })
                     } else {
                        Swal.fire({
                           title: "Berhasil",
                           text: hasil.msg,
                           icon: "error",
                           showConfirmButton: false,
                           timer: 2000
                        })
                     }
                  }
               })
            }
         })
      })
   })
</script>

<?= $this->endSection() ?>